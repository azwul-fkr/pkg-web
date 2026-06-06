<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Evaluation;
use App\Models\Period;
use App\Services\EvaluationService;

class MonitoringController extends Controller
{
    public function index(
        Request $request,
        EvaluationService $service
    ) {
        $periods = Period::all();

        $selectedPeriod = $request->period_id;

        $query = Assignment::with([
            'penilai',
            'guru.user',
            'period'
        ]);

        // FILTER PERIODE
        if ($selectedPeriod) {

            $query->where(
                'period_id',
                $selectedPeriod
            );
        }

        $assignments = $query->latest()->get();

        $monitorings = [];

        foreach ($assignments as $assignment) {

            // cek apakah sudah dinilai
            $evaluation = Evaluation::where([
                'guru_id' => $assignment->guru_id,
                'user_id' => $assignment->penilai_id,
                'period_id' => $assignment->period_id,
            ])->first();

            $status = 'belum_mulai';

            $nilaiAkhir = null;

            if ($evaluation) {

                $status = $evaluation->status;

                $nilaiAkhir =
                    $service->calculateFinalScore(
                        $evaluation->id
                    );
            }

            $monitorings[] = [

                'guru' =>
                $assignment->guru->user->name,

                'subject' =>
                $assignment->guru->subject,

                'penilai' =>
                $assignment->penilai->name,

                'periode' =>
                $assignment->period->name,

                'status' => $status,

                'nilai_akhir' => $nilaiAkhir,

                'evaluation_id' => $evaluation?->id,
            ];
        }

        return view(
            'admin.monitoring.index',
            compact(
                'monitorings',
                'periods',
                'selectedPeriod'
            )
        );
    }

    public function detail(
        $id,
        EvaluationService $service
    ) {
        $evaluation = Evaluation::with([
            'guru.user',
            'period',
            'penilai',
            'scores.indikator.subKriteria.kriteria'
        ])->findOrFail($id);

        /*
    =====================================
    GROUP SCORE
    =====================================
    */

        $groupedScores = [];

        foreach ($evaluation->scores as $score) {

            $sub =
                $score->indikator->subKriteria;

            $subId = $sub->id;

            if (!isset($groupedScores[$subId])) {

                $groupedScores[$subId] = [

                    'kriteria' =>
                    $sub->kriteria->name,

                    'kompetensi' =>
                    $sub->name,

                    'kode' =>
                    $sub->kode,

                    'scores' => [],
                ];
            }

            $groupedScores[$subId]['scores'][] = [

                'indikator' =>
                $score->indikator->name,

                'nilai' =>
                $score->nilai,

                'comment' =>
                $score->comment,
            ];
        }

        $finalScore =
            $service->calculateFinalScore(
                $evaluation->id
            );

        return view(
            'admin.monitoring.detail',
            compact(
                'evaluation',
                'groupedScores',
                'finalScore'
            )
        );
    }

    public function review(
        Request $request,
        $id,
        EvaluationService $service
    ) {
        $evaluation = Evaluation::findOrFail($id);
        $autoRecommendation = $service->generateRecommendations(
            $evaluation->id
        );

        $manualRecommendation = trim((string) $request->recommendation);

        $recommendationText = $autoRecommendation['text'];

        if ($manualRecommendation !== '') {
            $recommendationText =
                $manualRecommendation
                . "\n\nAI Recommendation Engine:\n"
                . $autoRecommendation['text'];
        }

        /*
    =====================================
    APPROVE
    =====================================
    */

        if ($request->action == 'approve') {

            $evaluation->update([
                'status' => 'finalized',
                'revision_note' => null,
                'feedback' => $request->feedback,
                'recommendation' => $recommendationText,
            ]);

            return redirect()
                ->route('admin.monitoring.index')
                ->with(
                    'success',
                    'Penilaian berhasil difinalisasi'
                );
        }

        /*
    =====================================
    REVISI
    =====================================
    */

        $evaluation->update([
            'status' => 'revised',

            'revision_note' =>
            $request->revision_note,

            'feedback' =>
            $request->feedback,

            'recommendation' =>
            $recommendationText,
        ]);

        return redirect()
            ->route('admin.monitoring.index')
            ->with(
                'success',
                'Revisi berhasil dikirim'
            );
    }
}
