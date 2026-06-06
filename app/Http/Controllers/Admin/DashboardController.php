<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Guru;
use App\Models\Evidence;
use App\Models\Evaluation;
use App\Models\Period;

use App\Services\EvaluationService;

class DashboardController extends Controller
{
    public function index(
        EvaluationService $service
    ) {
        /*
        =====================================
        TOTAL DATA
        =====================================
        */

        $totalGuru =
            Guru::count();

        $totalPenilai =
            User::whereHas(
                'role',
                fn($q) =>
                $q->where(
                    'name',
                    'penilai'
                )
            )->count();

        $totalEvidence =
            Evidence::count();

        $totalEvaluation =
            Evaluation::count();

        /*
        =====================================
        EVIDENCE STATUS
        =====================================
        */

        $approvedEvidence =
            Evidence::where(
                'status',
                'approved'
            )->count();

        $pendingEvidence =
            Evidence::where(
                'status',
                'pending'
            )->count();

        $rejectedEvidence =
            Evidence::where(
                'status',
                'rejected'
            )->count();

        /*
        =====================================
        ACTIVE PERIOD
        =====================================
        */

        $activePeriod =
            Period::where(
                'is_active',
                true
            )->first();

        /*
        =====================================
        RANKING GURU
        =====================================
        */

        $rankingGuru = [];

        $evaluations =
            Evaluation::with([
                'guru.user'
            ])

            ->where(
                'status',
                'finalized'
            )

            ->get();

        foreach ($evaluations as $evaluation) {

            $score =
                $service->calculateFinalScore(
                    $evaluation->id
                );

            $rankingGuru[] = [

                'guru' =>
                $evaluation->guru,

                'score' =>
                $score,
            ];
        }

        /*
        =====================================
        SORTING
        =====================================
        */

        usort(
            $rankingGuru,
            fn($a, $b) =>
            $b['score']
                <=>
                $a['score']
        );

        $topTeachersChart = [
            'labels' => [],
            'scores' => [],
        ];

        foreach (array_slice($rankingGuru, 0, 5) as $item) {
            $topTeachersChart['labels'][] =
                $item['guru']->user->name;
            $topTeachersChart['scores'][] =
                $item['score'];
        }

        $evaluationStatusCounts = [
            'draft' => Evaluation::where('status', 'draft')->count(),
            'submitted' => Evaluation::where('status', 'submitted')->count(),
            'revised' => Evaluation::where('status', 'revised')->count(),
            'finalized' => Evaluation::where('status', 'finalized')->count(),
        ];

        $periodPerformance = [
            'labels' => [],
            'scores' => [],
        ];

        $periods = Period::orderBy('start_date')->get();

        foreach ($periods as $period) {
            $periodEvaluations = Evaluation::where(
                'period_id',
                $period->id
            )
                ->where('status', 'finalized')
                ->get();

            if ($periodEvaluations->isEmpty()) {
                continue;
            }

            $scores = [];

            foreach ($periodEvaluations as $evaluation) {
                $scores[] = $service->calculateFinalScore(
                    $evaluation->id
                );
            }

            $periodPerformance['labels'][] = $period->name;
            $periodPerformance['scores'][] = round(
                array_sum($scores) / count($scores),
                2
            );
        }

        $schoolAverageByKriteria =
            $service->schoolAverageByKriteria(
                $activePeriod?->id
            );

        /*
        =====================================
        VIEW
        =====================================
        */

        return view(
            'admin.dashboard',
            compact(
                'totalGuru',
                'totalPenilai',
                'totalEvidence',
                'totalEvaluation',

                'approvedEvidence',
                'pendingEvidence',
                'rejectedEvidence',

                'activePeriod',

                'rankingGuru',
                'topTeachersChart',
                'evaluationStatusCounts',
                'periodPerformance',
                'schoolAverageByKriteria'
            )
        );
    }
}
    
