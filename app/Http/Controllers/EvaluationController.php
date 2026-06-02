<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Period;
use App\Models\Evaluation;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Score;
use App\Services\EvaluationService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Assignment;
use App\Models\Evidence;
use App\Models\Indikator;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    // Method Untuk Menampilkan Daftar Guru
    public function guruList()
    {
        $period = Period::where('is_active', true)
            ->first();

        if (!$period) {
            return back()->with('error', 'Periode aktif belum tersedia');
        }

        $assignments = Assignment::with([
            'guru.user'
        ])

            ->where('penilai_id', auth()->id())

            ->where('period_id', $period->id)

            ->get();

        /*
    =====================================
    TAMBAH STATUS EVALUATION
    =====================================
    */

        foreach ($assignments as $assignment) {

            $evaluation = Evaluation::where([
                'guru_id' => $assignment->guru_id,
                'user_id' => auth()->id(),
                'period_id' => $period->id,
            ])->first();

            $assignment->evaluation_status =
                $evaluation
                ? $evaluation->status
                : 'belum_mulai';

            $assignment->evaluation_id =
                $evaluation?->id;
        }

        return view(
            'penilai.guru.index',
            compact('assignments')
        );
    }

    // Method Untuk Menampilkan Form Penilaian
    public function create($guruId)
    {
        $guru = Guru::with([
            'user',
            'jabatan'
        ])->findOrFail($guruId);

        /*
    =====================================
    EVIDENCE APPROVED
    =====================================
    */

        $evidences = $guru->evidences()
            ->where('status', 'approved')
            ->get();

        /*
    =====================================
    STRUKTUR PENILAIAN
    =====================================
    */

        $kriterias = Kriteria::with([
            'subKriterias.indikators.indikatorScores'
        ])->get();

        /*
    =====================================
    TOTAL INDIKATOR
    =====================================
    */

        $totalIndikator = 0;

        foreach ($kriterias as $kriteria) {

            foreach ($kriteria->subKriterias as $sub) {

                $totalIndikator +=
                    $sub->indikators->count();
            }
        }

        /*
    =====================================
    PROGRESS DEFAULT
    =====================================
    */

        $completedIndikator = 0;

        $progressPercentage = 0;

        return view(
            'penilai.penilaian.create',
            compact(
                'guru',
                'evidences',
                'kriterias',
                'totalIndikator',
                'completedIndikator',
                'progressPercentage'
            )
        );
    }

    // Method Untuk Menyimpan Penilaian
    public function store(Request $request, $guruId)
    {
        /*
    =====================================================
    PERIOD
    =====================================================
    */

        $period = Period::where(
            'is_active',
            true
        )->first();

        if (!$period) {

            return back()->with(
                'error',
                'Periode aktif belum tersedia'
            );
        }

        /*
    =====================================================
    LOCK VALIDATION
    =====================================================
    */

        if ($period->is_locked) {

            return back()->with(
                'error',
                'Periode penilaian sedang dikunci'
            );
        }

        $validated = $request->validate([
            'scores' => ['required', 'array', 'min:1'],
            'scores.*' => ['required', 'integer', 'min:1', 'max:5'],
            'comments' => ['nullable', 'array'],
            'comments.*' => ['nullable', 'string'],
        ]);

        $evaluation = DB::transaction(function () use ($guruId, $period, $validated, $request) {

            $evaluation = Evaluation::firstOrCreate(
                [
                    'guru_id' => $guruId,
                    'user_id' => auth()->id(),
                    'period_id' => $period->id,
                ],
                [
                    'status' => 'draft',
                ]
            );

            $this->syncScores(
                $evaluation,
                $validated['scores'],
                $request->input('comments', [])
            );

            $evaluation->update([
                'status' => 'draft'
            ]);

            return $evaluation;
        });

        /*
    =====================================================
    REDIRECT
    =====================================================
    */

        return redirect()

            ->route(
                'penilai.penilaian.review',
                $evaluation->id
            )

            ->with(
                'success',
                'Draft berhasil disimpan'
            );
    }
    public function review($id)
    {
        /*
    =====================================================
    EVALUATION
    =====================================================
    */

        $evaluation = Evaluation::with([

            'guru.user',
            'guru.jabatan',
            'scores.indikator.subKriteria.kriteria',
            'period'

        ])

            ->findOrFail($id);

        /*
    =====================================================
    GURU
    =====================================================
    */

        $guru = $evaluation->guru;

        /*
    =====================================================
    EVIDENCE
    =====================================================
    */

        $evidences = Evidence::where(
            'guru_id',
            $guru->id
        )
            ->latest()
            ->get();

        /*
    =====================================================
    GROUPED SCORES
    =====================================================
    */

        $groupedScores = [];

        foreach ($evaluation->scores as $score) {

            $indikator =
                $score->indikator;

            $sub =
                $indikator->subKriteria;

            $kriteria =
                $sub->kriteria;

            $key =
                $kriteria->id . '-' . $sub->id;

            if (!isset($groupedScores[$key])) {

                $groupedScores[$key] = [

                    'kriteria' =>
                    $kriteria->name,

                    'kode' =>
                    $sub->kode ?? '-',

                    'kompetensi' =>
                    $sub->name,

                    'bobot_sub' =>
                    $sub->bobot ?? 0,

                    'bobot_kriteria' =>
                    $kriteria->bobot ?? 0,

                    'scores' => []

                ];
            }

            $groupedScores[$key]['scores'][] = [

                'indikator' =>
                $indikator->name,

                'nilai' =>
                $score->nilai,

                'comment' =>
                $score->comment,

            ];
        }

        /*
    =====================================================
    FINAL SCORE
    =====================================================
    */

        $totalNilai = $evaluation
            ->scores
            ->sum('nilai');

        $totalIndikator = $evaluation
            ->scores
            ->count();

        $finalScore = 0;

        if ($totalIndikator > 0) {

            $finalScore = round(
                $totalNilai / $totalIndikator,
                2
            );
        }

        /*
    =====================================================
    PROGRESS
    =====================================================
    */

        $completedIndikator =
            $evaluation
            ->scores
            ->count();

        /*
    =====================================================
    TOTAL INDIKATOR SYSTEM
    =====================================================
    */

        $allIndikator =
            Indikator::count();

        $progressPercentage = 0;

        if ($allIndikator > 0) {

            $progressPercentage = round(

                ($completedIndikator / $allIndikator) * 100

            );
        }

        /*
    =====================================================
    VIEW
    =====================================================
    */

        return view(
            'penilai.penilaian.review',
            compact(
                'evaluation',
                'guru',
                'evidences',
                'groupedScores',
                'finalScore',
                'completedIndikator',
                'totalIndikator',
                'progressPercentage'
            )
        );
    }
    // Method Untuk Menampilkan Hasil Penilaian
    public function hasil(Request $request, EvaluationService $service)
    {
        $periods = Period::all();

        $selectedPeriod = $request->period_id;

        $query = Evaluation::with([
            'guru.user',
            'period'
        ]);

        // filter periode
        if ($selectedPeriod) {
            $query->where('period_id', $selectedPeriod);
        }

        $evaluations = $query->get();

        $results = [];

        foreach ($evaluations as $evaluation) {

            $finalScore =
                $service->calculateFinalScore($evaluation->id);

            $results[] = [
                'evaluation_id' => $evaluation->id,
                'guru' => $evaluation->guru->user->name,
                'periode' => $evaluation->period->name,
                'nilai_akhir' => $finalScore
            ];
        }

        // ranking
        usort($results, function ($a, $b) {
            return $b['nilai_akhir'] <=> $a['nilai_akhir'];
        });

        return view(
            'penilai.hasil.index',
            compact(
                'results',
                'periods',
                'selectedPeriod'
            )
        );
    }

    //Method Untuk Menampilkan Detail Hasil Penilaian
    public function detail(
        $id,
        EvaluationService $service
    ) {
        $evaluation = Evaluation::with([
            'guru.user',
            'period',
            'scores.indikator.subKriteria.kriteria'
        ])->findOrFail($id);

        $finalScore =
            $service->calculateFinalScore($evaluation->id);

        /*
    =====================================
    GROUP BY KOMPETENSI
    =====================================
    */

        $groupedScores = [];

        foreach ($evaluation->scores as $score) {

            $sub =
                $score->indikator->subKriteria;

            $subId = $sub->id;

            if (!isset($groupedScores[$subId])) {

                $groupedScores[$subId] = [
                    'kriteria' => $sub->kriteria->name,
                    'kompetensi' => $sub->name,
                    'kode' => $sub->kode,
                    'bobot_sub' => $sub->bobot,
                    'bobot_kriteria' =>
                    $sub->kriteria->bobot,
                    'indikators' => [],
                ];
            }

            $groupedScores[$subId]['indikators'][] = [
                'indikator' =>
                $score->indikator->name,

                'nilai' => $score->nilai,

                'comment' => $score->comment,
            ];
        }

        $analytics =
            $service->analytics(
                $evaluation->id
            );

        $bestWorst =
            $service->bestAndWorstCompetency(
                $analytics
            );

        return view(
            'penilai.hasil.detail',
            compact(
                'evaluation',
                'groupedScores',
                'finalScore',
                'analytics',
                'bestWorst',
            )
        );
    }

    // Method Untuk Export Hasil Penilaian ke PDF
    public function exportPdf(
        Request $request,
        EvaluationService $service
    ) {
        $query = Evaluation::with([
            'guru.user',
            'period'
        ]);

        if ($request->period_id) {
            $query->where(
                'period_id',
                $request->period_id
            );
        }

        $evaluations = $query->get();

        $results = [];

        foreach ($evaluations as $evaluation) {

            $finalScore =
                $service->calculateFinalScore($evaluation->id);

            $results[] = [
                'guru' => $evaluation->guru->user->name,
                'periode' => $evaluation->period->name,
                'nilai_akhir' => $finalScore
            ];
        }

        usort($results, function ($a, $b) {
            return $b['nilai_akhir'] <=> $a['nilai_akhir'];
        });

        $pdf = Pdf::loadView(
            'penilai.hasil.pdf',
            compact('results')
        );

        return $pdf->download('laporan-penilaian.pdf');
    }

    public function edit($id)
    {
        /*
    =====================================================
    EVALUATION
    =====================================================
    */

        $evaluation = Evaluation::with([

            'guru.user',
            'guru.jabatan',
            'scores',
            'period'

        ])

            ->findOrFail($id);

        /*
    =====================================================
    GURU
    =====================================================
    */

        $guru = $evaluation->guru;

        /*
    =====================================================
    KRITERIA
    =====================================================
    */

        $kriterias = Kriteria::with([
            'subKriterias.indikators.indikatorScores'
        ])->get();

        /*
    =====================================================
    EVIDENCES
    =====================================================
    */

        $evidences = Evidence::where(
            'guru_id',
            $guru->id
        )
            ->latest()
            ->get();

        /*
    =====================================================
    COMPLETED INDIKATOR
    =====================================================
    */

        $completedIndikator =
            $evaluation
            ->scores
            ->count();

        /*
    =====================================================
    TOTAL INDIKATOR
    =====================================================
    */

        $totalIndikator = 0;

        foreach ($kriterias as $kriteria) {

            foreach ($kriteria->subKriterias as $sub) {

                $totalIndikator +=
                    $sub->indikators->count();
            }
        }

        /*
    =====================================================
    PROGRESS PERCENTAGE
    =====================================================
    */

        $progressPercentage = 0;

        if ($totalIndikator > 0) {

            $progressPercentage = round(

                ($completedIndikator / $totalIndikator) * 100

            );
        }

        /*
    =====================================================
    KRITERIA PROGRESS
    =====================================================
    */

        $kriteriaProgress = [];

        foreach ($kriterias as $kriteria) {

            $completed = 0;

            $total = 0;

            foreach ($kriteria->subKriterias as $sub) {

                foreach ($sub->indikators as $indikator) {

                    $total++;

                    $hasScore = $evaluation
                        ->scores
                        ->where(
                            'indikator_id',
                            $indikator->id
                        )
                        ->count();

                    if ($hasScore > 0) {

                        $completed++;
                    }
                }
            }

            $percentage = 0;

            if ($total > 0) {

                $percentage = round(
                    ($completed / $total) * 100
                );
            }

            $kriteriaProgress[] = [

                'name' =>
                $kriteria->name,

                'completed' =>
                $completed,

                'total' =>
                $total,

                'percentage' =>
                $percentage,
            ];
        }

        return view(
            'penilai.penilaian.edit',
            compact(
                'evaluation',
                'guru',
                'kriterias',
                'evidences',
                'completedIndikator',
                'totalIndikator',
                'progressPercentage',
                'kriteriaProgress'
            )
        );
    }

    public function update(Request $request, $id)
    {
        /*
    =====================================================
    EVALUATION
    =====================================================
    */

        $evaluation = Evaluation::findOrFail($id);

        /*
    =====================================================
    PERIOD
    =====================================================
    */

        $period = Period::where(
            'is_active',
            true
        )->first();

        if (!$period) {

            return back()->with(
                'error',
                'Periode aktif belum tersedia'
            );
        }

        /*
    =====================================================
    LOCK VALIDATION
    =====================================================
    */

        if ($period->is_locked) {

            return back()->with(
                'error',
                'Periode penilaian sedang dikunci'
            );
        }

        /*
    =====================================================
    VALIDATION
    =====================================================
    */

        $validated = $request->validate([
            'scores' => ['required', 'array', 'min:1'],
            'scores.*' => ['required', 'integer', 'min:1', 'max:5'],
            'comments' => ['nullable', 'array'],
            'comments.*' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($evaluation, $validated, $request) {

            $this->syncScores(
                $evaluation,
                $validated['scores'],
                $request->input('comments', [])
            );

            $evaluation->update([
                'status' => 'draft'
            ]);
        });

        /*
    =====================================================
    REDIRECT
    =====================================================
    */

        return redirect()

            ->route(
                'penilai.penilaian.review',
                $evaluation->id
            )

            ->with(
                'success',
                'Draft berhasil disimpan'
            );
    }

    public function finalSubmit($id)
    {
        /*
    =====================================================
    EVALUATION
    =====================================================
    */

        $evaluation = Evaluation::findOrFail($id);

        /*
    =====================================================
    UPDATE STATUS
    =====================================================
    */

        $evaluation->update([

            'status' => 'submitted'

        ]);

        /*
    =====================================================
    REDIRECT
    =====================================================
    */

        return redirect()

            ->route('penilai.guru.index')

            ->with(
                'success',
                'Penilaian berhasil disubmit'
            );
    }

    private function syncScores(
        Evaluation $evaluation,
        array $scores,
        array $comments = []
    ): void {
        $indikatorIds = array_map(
            'intval',
            array_keys($scores)
        );

        $validIndikatorIds = Indikator::whereIn(
            'id',
            $indikatorIds
        )->pluck('id')->map(fn ($id) => (int) $id)->all();

        $invalidIndikatorIds = array_diff(
            $indikatorIds,
            $validIndikatorIds
        );

        if (!empty($invalidIndikatorIds)) {
            abort(422, 'Data indikator penilaian tidak valid');
        }

        Score::where(
            'evaluation_id',
            $evaluation->id
        )->whereNotIn(
            'indikator_id',
            $validIndikatorIds
        )->delete();

        foreach ($scores as $indikatorId => $nilai) {

            Score::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'indikator_id' => (int) $indikatorId,
                ],
                [
                    'nilai' => (int) $nilai,
                    'comment' => $comments[$indikatorId] ?? null,
                ]
            );
        }
    }
}
