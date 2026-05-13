<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Period;
use App\Models\Kriteria;
use App\Models\SelfAssessment;
use App\Models\SelfAssessmentScore;
use Illuminate\Http\Request;

class SelfAssessmentController extends Controller
{
    public function index()
    {
        /*
    =====================================================
    GURU LOGIN
    =====================================================
    */

        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        /*
    =====================================================
    PERIODE AKTIF
    =====================================================
    */

        $periods = Period::where(
            'is_active',
            true
        )
            ->where(
                'is_locked',
                false
            )
            ->latest()
            ->get();

        /*
    =====================================================
    SELF ASSESSMENT
    =====================================================
    */

        $assessments = SelfAssessment::with([
            'period'
        ])

            ->where(
                'guru_id',
                $guru->id
            )

            ->latest()

            ->get();

        /*
    =====================================================
    RETURN VIEW
    =====================================================
    */

        return view(
            'guru.self-assessment.index',
            compact(
                'assessments',
                'periods',
                'guru'
            )
        );
    }

    public function create()
    {
        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->first();

        $period = Period::where(
            'is_active',
            true
        )->first();

        // cek existing
        $existing = SelfAssessment::where([
            'guru_id' => $guru->id,
            'period_id' => $period->id,
        ])->first();

        if ($existing) {

            return redirect()
                ->route(
                    'guru.self-assessment.review',
                    $existing->id
                );
        }

        $kriterias = Kriteria::with([
            'subKriterias.indikators.indikatorScores'
        ])->get();

        return view(
            'guru.self-assessment.create',
            compact(
                'guru',
                'period',
                'kriterias'
            )
        );
    }

    public function store(Request $request)
    {
        $guru = Guru::where(
            'user_id',
            auth()->id()
        )->firstOrFail();

        $request->validate([

            'period_id' =>
            'required|exists:periods,id'

        ]);

        /*
    =====================================
    CREATE / GET
    =====================================
    */

        $assessment = SelfAssessment::firstOrCreate(

            [
                'guru_id' =>
                $guru->id,

                'period_id' =>
                $request->period_id,
            ],

            [
                'status' => 'draft'
            ]
        );

        return redirect()

            ->route(
                'guru.self-assessment.review',
                $assessment->id
            )

            ->with(
                'success',
                'Self assessment berhasil dibuat'
            );
    }

    public function review($id)
    {
        $assessment = SelfAssessment::with([

            'period',

            'scores',

        ])->findOrFail($id);

        $kriterias = Kriteria::with([

            'subKriterias.indikators.indikatorScores'

        ])->get();

        return view(
            'guru.self-assessment.review',
            compact(
                'assessment',
                'kriterias'
            )
        );
    }

    public function finalSubmit($id)
    {
        $assessment = SelfAssessment::findOrFail($id);

        $assessment->update([
            'status' => 'submitted'
        ]);

        return redirect()
            ->route(
                'guru.self-assessment.index'
            )
            ->with(
                'success',
                'Self assessment berhasil disubmit'
            );
    }

    public function update(Request $request, $id)
    {
        $assessment =
            SelfAssessment::findOrFail($id);

        /*
    =====================================
    DELETE OLD SCORE
    =====================================
    */

        $assessment->scores()->delete();

        /*
    =====================================
    SAVE NEW SCORE
    =====================================
    */

        if ($request->filled('scores')) {

            foreach (
                $request->scores
                as $indikatorId => $nilai
            ) {

                SelfAssessmentScore::create([

                    'self_assessment_id' =>
                    $assessment->id,

                    'indikator_id' =>
                    $indikatorId,

                    'nilai' =>
                    $nilai,

                    'comment' =>
                    $request->comments[$indikatorId]
                        ?? null,
                ]);
            }
        }

        /*
    =====================================
    STATUS
    =====================================
    */

        $assessment->update([

            'status' =>
            $request->submit_type == 'submit'
                ? 'submitted'
                : 'draft'

        ]);

        return redirect()

            ->route(
                'guru.self-assessment.index',
                $assessment->id
            )

            ->with(
                'success',
                $request->submit_type == 'submit'
                    ? 'Self assessment berhasil dikirim'
                    : 'Draft berhasil disimpan'
            );
    }
}
