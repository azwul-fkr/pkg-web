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

                'rankingGuru'
            )
        );
    }
}
    