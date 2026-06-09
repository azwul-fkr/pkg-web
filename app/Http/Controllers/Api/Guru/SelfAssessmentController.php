<?php

namespace App\Http\Controllers\Api\Guru;

use App\Models\Guru;
use App\Models\Kriteria;
use App\Models\Period;
use App\Models\SelfAssessment;
use App\Models\SelfAssessmentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelfAssessmentController extends BaseGuruApiController
{
    public function index()
    {
        $guru = $this->currentGuru();

        $assessments = SelfAssessment::with('period')
            ->where('guru_id', $guru->id)
            ->latest()
            ->get()
            ->map(fn ($assessment) => $this->formatAssessment($assessment))
            ->values();

        $periods = Period::where('is_active', true)
            ->where('is_locked', false)
            ->latest()
            ->get()
            ->map(fn ($period) => [
                'id' => $period->id,
                'name' => $period->name,
                'is_active' => (bool) $period->is_active,
                'is_locked' => (bool) $period->is_locked,
            ])
            ->values();

        return $this->success([
            'assessments' => $assessments,
            'periods' => $periods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:periods,id',
        ]);

        $guru = $this->currentGuru();

        $assessment = SelfAssessment::firstOrCreate([
            'guru_id' => $guru->id,
            'period_id' => $validated['period_id'],
        ], [
            'status' => 'draft',
        ]);

        return $this->success([
            'assessment' => $this->formatAssessment($assessment->load('period')),
        ], 'Self assessment berhasil dibuat.', 201);
    }

    public function show($id)
    {
        $guru = $this->currentGuru();

        $assessment = SelfAssessment::with([
            'period',
            'scores.indikator.indikatorScores',
        ])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);

        $kriterias = Kriteria::with([
            'subKriterias.indikators.indikatorScores',
        ])->get();

        return $this->success([
            'assessment' => $this->formatAssessment($assessment),
            'kriterias' => $kriterias,
            'scores' => $assessment->scores->map(fn ($score) => [
                'id' => $score->id,
                'indikator_id' => $score->indikator_id,
                'nilai' => $score->nilai,
                'comment' => $score->comment,
            ])->values(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'scores' => 'nullable|array',
            'comments' => 'nullable|array',
            'submit_type' => 'nullable|in:draft,submit',
        ]);

        $guru = $this->currentGuru();

        $assessment = SelfAssessment::where('guru_id', $guru->id)->findOrFail($id);

        DB::transaction(function () use ($request, $assessment) {
            $assessment->scores()->delete();

            foreach (($request->input('scores', []) ?? []) as $indikatorId => $nilai) {
                SelfAssessmentScore::create([
                    'self_assessment_id' => $assessment->id,
                    'indikator_id' => $indikatorId,
                    'nilai' => $nilai,
                    'comment' => $request->input('comments.' . $indikatorId),
                ]);
            }

            $assessment->update([
                'status' => $request->input('submit_type') === 'submit' ? 'submitted' : 'draft',
            ]);
        });

        return $this->success([
            'assessment' => $this->formatAssessment($assessment->fresh(['period', 'scores'])),
        ], $request->input('submit_type') === 'submit'
            ? 'Self assessment berhasil dikirim.'
            : 'Draft berhasil disimpan.');
    }

    public function submit($id)
    {
        $guru = $this->currentGuru();

        $assessment = SelfAssessment::where('guru_id', $guru->id)->findOrFail($id);
        $assessment->update(['status' => 'submitted']);

        return $this->success([
            'assessment' => $this->formatAssessment($assessment->fresh(['period', 'scores'])),
        ], 'Self assessment berhasil disubmit.');
    }

    private function formatAssessment(SelfAssessment $assessment): array
    {
        $assessment->loadMissing([
            'period',
            'scores',
        ]);

        return [
            'id' => $assessment->id,
            'guru_id' => $assessment->guru_id,
            'period_id' => $assessment->period_id,
            'period' => $assessment->period?->name,
            'status' => $assessment->status,
            'scores_count' => $assessment->scores?->count() ?? 0,
            'created_at' => $assessment->created_at,
            'updated_at' => $assessment->updated_at,
        ];
    }
}
