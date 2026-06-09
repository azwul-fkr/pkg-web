<?php

namespace App\Http\Controllers\Api\Guru;

use App\Models\Evaluation;
use App\Models\Evidence;
use App\Models\Guru;
use App\Models\Period;
use App\Models\SelfAssessment;
use App\Models\TeacherReflection;
use App\Services\EvaluationService;

class DashboardController extends BaseGuruApiController
{
    public function index(EvaluationService $service)
    {
        $guru = $this->currentGuru();
        $period = Period::where('is_active', true)->first();

        $reflection = TeacherReflection::with('evaluation.period')
            ->where('guru_id', $guru->id)
            ->latest()
            ->first();

        $totalEvidence = Evidence::where('guru_id', $guru->id)->count();
        $approvedEvidence = Evidence::where('guru_id', $guru->id)->where('status', 'approved')->count();
        $pendingEvidence = Evidence::where('guru_id', $guru->id)->where('status', 'pending')->count();
        $rejectedEvidence = Evidence::where('guru_id', $guru->id)->where('status', 'rejected')->count();

        $latestEvaluation = Evaluation::where('guru_id', $guru->id)
            ->when($period?->id, fn ($query) => $query->where('period_id', $period->id))
            ->latest()
            ->first();

        $evaluation = null;
        $pendingEvaluation = null;

        if ($latestEvaluation?->status === 'finalized') {
            $evaluation = $latestEvaluation;
        } elseif ($latestEvaluation) {
            $pendingEvaluation = $latestEvaluation;
        }

        $finalScore = null;
        $analytics = null;
        $gapAnalysis = null;
        $recommendationEngine = null;
        $schoolComparison = [];
        $competencyComparisonChart = [
            'labels' => [],
            'guruScores' => [],
            'schoolScores' => [],
        ];
        $bestWorst = [
            'best' => null,
            'worst' => null,
        ];

        if ($evaluation) {
            $selfAssessment = SelfAssessment::where('guru_id', $guru->id)
                ->when($period?->id, fn ($query) => $query->where('period_id', $period->id))
                ->where('status', 'submitted')
                ->latest()
                ->first();

            if ($selfAssessment) {
                $gapAnalysis = $service->gapAnalysis($evaluation->id, $selfAssessment->id);
            }

            $finalScore = $service->calculateFinalScore($evaluation->id);
            $analytics = $service->analytics($evaluation->id);
            $recommendationEngine = $service->generateRecommendations($evaluation->id);
            $schoolComparison = $service->compareEvaluationToSchool($evaluation->id);

            foreach ($schoolComparison as $comparison) {
                $competencyComparisonChart['labels'][] = $comparison['kriteria'];
                $competencyComparisonChart['guruScores'][] = $comparison['guru_score'];
                $competencyComparisonChart['schoolScores'][] = $comparison['school_average'];
            }

            $bestWorst = $service->bestAndWorstCompetency($analytics);
        }

        $trendLabels = [];
        $trendScores = [];

        $pastEvaluations = Evaluation::where('guru_id', $guru->id)
            ->where('status', 'finalized')
            ->latest()
            ->take(6)
            ->get()
            ->reverse();

        foreach ($pastEvaluations as $ev) {
            $trendLabels[] = $ev->period->name ?? $ev->created_at->format('Y-m-d');
            $trendScores[] = $service->calculateFinalScore($ev->id);
        }

        return $this->success([
            'guru' => $this->formatGuru($guru),
            'period' => $period ? [
                'id' => $period->id,
                'name' => $period->name,
                'is_active' => (bool) $period->is_active,
                'is_locked' => (bool) $period->is_locked,
            ] : null,
            'summary' => [
                'total_evidence' => $totalEvidence,
                'approved_evidence' => $approvedEvidence,
                'pending_evidence' => $pendingEvidence,
                'rejected_evidence' => $rejectedEvidence,
                'final_score' => $finalScore,
            ],
            'reflection' => $reflection ? [
                'id' => $reflection->id,
                'evaluation_id' => $reflection->evaluation_id,
                'period' => $reflection->evaluation?->period?->name,
                'reflection' => $reflection->reflection,
                'improvement_plan' => $reflection->improvement_plan,
                'created_at' => $reflection->created_at,
            ] : null,
            'evaluation' => $evaluation ? [
                'id' => $evaluation->id,
                'status' => $evaluation->status,
                'feedback' => $evaluation->feedback,
                'recommendation' => $evaluation->recommendation,
                'period' => $evaluation->period?->name,
            ] : null,
            'pending_evaluation' => $pendingEvaluation ? [
                'id' => $pendingEvaluation->id,
                'status' => $pendingEvaluation->status,
                'period' => $pendingEvaluation->period?->name,
            ] : null,
            'analytics' => $analytics,
            'gap_analysis' => $gapAnalysis,
            'recommendation_engine' => $recommendationEngine,
            'school_comparison' => $schoolComparison,
            'comparison_chart' => $competencyComparisonChart,
            'best_worst' => $bestWorst,
            'trend' => [
                'labels' => $trendLabels,
                'scores' => $trendScores,
            ],
        ]);
    }
}
