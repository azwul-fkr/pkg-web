<?php

namespace App\Services;

use App\Models\Evaluation;

class EvaluationService
{
    public function calculateFinalScore($evaluationId)
    {
        $evaluation = Evaluation::with([
            'scores.indikator.subKriteria.kriteria'
        ])->findOrFail($evaluationId);

        $grouped = [];

        /*
        =====================================
        GROUP SCORE BERDASARKAN KOMPETENSI
        =====================================
        */

        foreach ($evaluation->scores as $score) {

            $subKriteria =
                $score->indikator->subKriteria;

            $subId = $subKriteria->id;

            if (!isset($grouped[$subId])) {

                $grouped[$subId] = [
                    'sub_kriteria' => $subKriteria,
                    'scores' => [],
                ];
            }

            $grouped[$subId]['scores'][]
                = $score->nilai;
        }

        /*
        =====================================
        HITUNG FINAL SCORE
        =====================================
        */

        $finalScore = 0;

        foreach ($grouped as $group) {

            $sub = $group['sub_kriteria'];

            // rata-rata indikator
            $avgIndikator =
                array_sum($group['scores'])
                /
                count($group['scores']);

            // bobot kompetensi
            $bobotSub =
                $sub->bobot;

            // bobot kriteria
            $bobotKriteria =
                $sub->kriteria->bobot;

            /*
            =====================================
            SCORE KOMPETENSI
            =====================================
            */

            $scoreKompetensi =
                $avgIndikator
                *
                ($bobotSub / 100)
                *
                ($bobotKriteria / 100);

            $finalScore += $scoreKompetensi;
        }

        /*
        =====================================
        NORMALISASI SCORE
        =====================================
        */

        return round($finalScore * 100, 2);
    }

    public function analytics($evaluationId)
    {
        $evaluation = Evaluation::with([
            'scores.indikator.subKriteria.kriteria'
        ])->findOrFail($evaluationId);

        $analytics = [];

        /*
    =====================================
    GROUPING
    =====================================
    */

        foreach ($evaluation->scores as $score) {

            $sub =
                $score->indikator->subKriteria;

            $kriteria =
                $sub->kriteria;

            $kriteriaId =
                $kriteria->id;

            /*
        =====================================
        INIT KRITERIA
        =====================================
        */

            if (!isset($analytics[$kriteriaId])) {

                $analytics[$kriteriaId] = [

                    'kriteria' =>
                    $kriteria->name,

                    'bobot' =>
                    $kriteria->bobot,

                    'subs' => [],
                ];
            }

            /*
        =====================================
        INIT SUB
        =====================================
        */

            if (!isset(
                $analytics[$kriteriaId]['subs'][$sub->id]
            )) {

                $analytics[$kriteriaId]['subs'][$sub->id] = [

                    'kode' =>
                    $sub->kode,

                    'kompetensi' =>
                    $sub->name,

                    'bobot' =>
                    $sub->bobot,

                    'scores' => [],
                ];
            }

            /*
        =====================================
        SAVE SCORE
        =====================================
        */

            $analytics[$kriteriaId]['subs'][$sub->id]['scores'][]
                = $score->nilai;
        }

        /*
    =====================================
    HITUNG ANALYTICS
    =====================================
    */

        foreach ($analytics as &$kriteria) {

            $kriteriaTotal = 0;

            $kriteriaCount = 0;

            foreach ($kriteria['subs'] as &$sub) {

                $avg =
                    array_sum($sub['scores'])
                    /
                    count($sub['scores']);

                $sub['average'] =
                    round($avg, 2);

                /*
            =====================================
            WEIGHTED SCORE
            =====================================
            */

                $weighted =
                    $avg
                    *
                    ($sub['bobot'] / 100)
                    *
                    ($kriteria['bobot'] / 100);

                $sub['weighted_score'] =
                    round($weighted * 100, 2);

                $kriteriaTotal += $avg;

                $kriteriaCount++;
            }

            /*
        =====================================
        RATA KRITERIA
        =====================================
        */

            $kriteria['average'] =
                $kriteriaCount > 0

                ? round(
                    $kriteriaTotal / $kriteriaCount,
                    2
                )

                : 0;
        }

        return $analytics;
    }

    public function bestAndWorstCompetency($analytics)
    {
        $allSubs = [];

        foreach ($analytics as $kriteria) {

            foreach ($kriteria['subs'] as $sub) {

                $allSubs[] = $sub;
            }
        }

        /*
    =====================================
    SORT ASC
    =====================================
    */

        usort($allSubs, function ($a, $b) {

            return $a['average']
                <=> $b['average'];
        });

        return [

            'worst' => $allSubs[0] ?? null,

            'best' =>
            $allSubs[count($allSubs) - 1]
                ?? null,
        ];
    }

    public function gapAnalysis(
        $evaluationId,
        $selfAssessmentId
    ) {
        /*
    =====================================
    LOAD DATA
    =====================================
    */

        $evaluation = \App\Models\Evaluation::with([
            'scores.indikator.subKriteria'
        ])->findOrFail($evaluationId);

        $selfAssessment =
            \App\Models\SelfAssessment::with([
                'scores.indikator.subKriteria'
            ])->findOrFail($selfAssessmentId);

        /*
    =====================================
    GROUP SELF
    =====================================
    */

        $selfGrouped = [];

        foreach ($selfAssessment->scores as $score) {

            $sub =
                $score->indikator->subKriteria;

            if (!isset($selfGrouped[$sub->id])) {

                $selfGrouped[$sub->id] = [
                    'kompetensi' => $sub->name,
                    'kode' => $sub->kode,
                    'scores' => [],
                ];
            }

            $selfGrouped[$sub->id]['scores'][]
                = $score->nilai;
        }

        /*
    =====================================
    GROUP PENILAI
    =====================================
    */

        $penilaiGrouped = [];

        foreach ($evaluation->scores as $score) {

            $sub =
                $score->indikator->subKriteria;

            if (!isset($penilaiGrouped[$sub->id])) {

                $penilaiGrouped[$sub->id] = [
                    'kompetensi' => $sub->name,
                    'kode' => $sub->kode,
                    'scores' => [],
                ];
            }

            $penilaiGrouped[$sub->id]['scores'][]
                = $score->nilai;
        }

        /*
    =====================================
    GAP ANALYSIS
    =====================================
    */

        $results = [];

        foreach ($selfGrouped as $subId => $self) {

            if (!isset($penilaiGrouped[$subId])) {
                continue;
            }

            $selfAvg =
                array_sum($self['scores'])
                /
                count($self['scores']);

            $penilaiAvg =
                array_sum(
                    $penilaiGrouped[$subId]['scores']
                )
                /
                count(
                    $penilaiGrouped[$subId]['scores']
                );

            $gap =
                round(
                    $penilaiAvg - $selfAvg,
                    2
                );

            $results[] = [

                'kode' =>
                $self['kode'],

                'kompetensi' =>
                $self['kompetensi'],

                'self_avg' =>
                round($selfAvg, 2),

                'penilai_avg' =>
                round($penilaiAvg, 2),

                'gap' =>
                $gap,
            ];
        }

        return $results;
    }
}
