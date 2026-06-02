<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use App\Models\Evaluation;
use App\Models\Guru;
use App\Models\Period;
use App\Services\EvaluationService;
use App\Models\SelfAssessment;
use App\Models\TeacherReflection;

class DashboardController extends Controller
{
    public function index(
        EvaluationService $service
    ) {
        /*
        =====================================
        GURU LOGIN
        =====================================
        */

        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->with([
            'user',
            'jabatan',
        ])->first();

        $period = Period::where(
            'is_active',
            true
        )->first();

        /*
    =====================================
    REFLECTION TERBARU
    =====================================
    */

        $reflection = TeacherReflection::with(
            'evaluation.period'
        )

            ->where(
                'guru_id',
                $guru->id
            )

            ->latest()

            ->first();

        $totalEvidence = 0;
        $approvedEvidence = 0;
        $pendingEvidence = 0;
        $rejectedEvidence = 0;
        $evaluation = null;
        $pendingEvaluation = null;
        $finalScore = null;
        $analytics = null;
        $gapAnalysis = null;
        $bestWorst = [
            'best' => null,
            'worst' => null,
        ];

        if (!$guru) {
            return view(
                'guru.dashboard',
                compact(
                    'guru',
                    'period',
                    'totalEvidence',
                    'approvedEvidence',
                    'pendingEvidence',
                    'rejectedEvidence',
                    'evaluation',
                    'pendingEvaluation',
                    'finalScore',
                    'analytics',
                    'bestWorst',
                    'gapAnalysis',
                )
            );
        }

        /*
        =====================================
        EVIDENCE
        =====================================
        */

        $totalEvidence = Evidence::where(
            'guru_id',
            $guru->id
        )->count();

        $approvedEvidence = Evidence::where(
            'guru_id',
            $guru->id
        )->where(
            'status',
            'approved'
        )->count();

        $pendingEvidence = Evidence::where(
            'guru_id',
            $guru->id
        )->where(
            'status',
            'pending'
        )->count();

        $rejectedEvidence = Evidence::where(
            'guru_id',
            $guru->id
        )->where(
            'status',
            'rejected'
        )->count();

        /*
        =====================================
        EVALUATION TERBARU
        =====================================
        */

        $latestEvaluation = Evaluation::where(
            'guru_id',
            $guru->id
        )

            ->where(
                'period_id',
                $period?->id
            )

            ->where(
                'status',
                'finalized'
            )

            ->latest()

            ->first();

        if ($latestEvaluation?->status === 'finalized') {

            $evaluation = $latestEvaluation;
        } else {

            $pendingEvaluation = $latestEvaluation;
        }

        /*
        =====================================
        FINAL SCORE
        =====================================
        */

        if ($evaluation) {

            $selfAssessment =
                SelfAssessment::where(
                    'guru_id',
                    $guru->id
                )

                ->where(
                    'period_id',
                    $period?->id
                )

                ->where(
                    'status',
                    'submitted'
                )

                ->latest()

                ->first();

            if ($selfAssessment) {

                $gapAnalysis =
                    $service->gapAnalysis(
                        $evaluation->id,
                        $selfAssessment->id
                    );
            }
        }

        if ($evaluation) {

            $finalScore =
                $service->calculateFinalScore(
                    $evaluation->id
                );

            $analytics =
                $service->analytics(
                    $evaluation->id
                );

            $bestWorst =
                $service->bestAndWorstCompetency(
                    $analytics
                );
        }

        return view(
            'guru.dashboard',
            compact(
                'guru',
                'period',
                'totalEvidence',
                'approvedEvidence',
                'pendingEvidence',
                'rejectedEvidence',
                'evaluation',
                'pendingEvaluation',
                'finalScore',
                'analytics',
                'bestWorst',
                'gapAnalysis',
                'reflection'
            )
        );
    }
}
