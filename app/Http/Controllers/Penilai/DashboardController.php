<?php

namespace App\Http\Controllers\Penilai;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Evaluation;
use App\Models\Period;
use App\Services\EvaluationService;

class DashboardController extends Controller
{
    public function index(
        EvaluationService $service
    ) {
        $activePeriod = Period::where(
            'is_active',
            true
        )->first();

        $assignmentsQuery = Assignment::with([
            'guru.user',
        ])->where(
            'penilai_id',
            auth()->id()
        );

        if ($activePeriod) {
            $assignmentsQuery->where(
                'period_id',
                $activePeriod->id
            );
        }

        $assignments = $assignmentsQuery->get();

        $evaluationsQuery = Evaluation::with([
            'guru.user',
        ])->where(
            'user_id',
            auth()->id()
        );

        if ($activePeriod) {
            $evaluationsQuery->where(
                'period_id',
                $activePeriod->id
            );
        }

        $evaluations = $evaluationsQuery->get();

        $statusCounts = [
            'belum_mulai' => 0,
            'draft' => 0,
            'submitted' => 0,
            'revised' => 0,
            'finalized' => 0,
        ];

        $evaluationMap = $evaluations->keyBy('guru_id');

        foreach ($assignments as $assignment) {
            $status = $evaluationMap[$assignment->guru_id]->status
                ?? 'belum_mulai';
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }

        $teacherPerformance = [];

        foreach ($evaluations as $evaluation) {
            if (!in_array($evaluation->status, ['submitted', 'finalized'])) {
                continue;
            }

            $teacherPerformance[] = [
                'guru' => $evaluation->guru->user->name,
                'status' => $evaluation->status,
                'score' => $service->calculateFinalScore(
                    $evaluation->id
                ),
            ];
        }

        usort($teacherPerformance, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $teacherPerformanceChart = [
            'labels' => [],
            'scores' => [],
        ];

        foreach (array_slice($teacherPerformance, 0, 6) as $item) {
            $teacherPerformanceChart['labels'][] =
                $item['guru'];
            $teacherPerformanceChart['scores'][] =
                $item['score'];
        }

        return view(
            'penilai.dashboard',
            compact(
                'activePeriod',
                'assignments',
                'statusCounts',
                'teacherPerformance',
                'teacherPerformanceChart'
            )
        );
    }
}
