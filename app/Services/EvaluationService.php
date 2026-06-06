<?php

namespace App\Services;

use App\Models\Evaluation;
use Illuminate\Support\Str;

class EvaluationService
{
    private const MAX_SCORE = 5;

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

    public function normalizeScore(
        float $average,
        int $maxScore = self::MAX_SCORE
    ): float {
        if ($maxScore <= 0) {
            return 0;
        }

        return round(($average / $maxScore) * 100, 2);
    }

    public function flattenAnalytics(
        array $analytics
    ): array {
        $flattened = [];

        foreach ($analytics as $kriteria) {
            foreach ($kriteria['subs'] as $sub) {
                $flattened[] = [
                    'kriteria' => $kriteria['kriteria'],
                    'kode' => $sub['kode'],
                    'kompetensi' => $sub['kompetensi'],
                    'average' => $sub['average'],
                    'normalized_score' => $this->normalizeScore(
                        $sub['average']
                    ),
                    'weighted_score' => $sub['weighted_score'],
                ];
            }
        }

        return $flattened;
    }

    public function schoolAverageByKriteria(
        ?int $periodId = null,
        ?int $excludeEvaluationId = null
    ): array {
        $query = Evaluation::where(
            'status',
            'finalized'
        );

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        if ($excludeEvaluationId) {
            $query->where('id', '!=', $excludeEvaluationId);
        }

        $evaluations = $query->get();

        $totals = [];

        foreach ($evaluations as $evaluation) {
            $analytics = $this->analytics($evaluation->id);

            foreach ($analytics as $item) {
                $key = $item['kriteria'];

                if (!isset($totals[$key])) {
                    $totals[$key] = [
                        'kriteria' => $item['kriteria'],
                        'total' => 0,
                        'count' => 0,
                    ];
                }

                $totals[$key]['total'] += $this->normalizeScore(
                    $item['average']
                );
                $totals[$key]['count']++;
            }
        }

        $results = [];

        foreach ($totals as $key => $item) {
            $results[$key] = round(
                $item['total'] / max($item['count'], 1),
                2
            );
        }

        return $results;
    }

    public function compareEvaluationToSchool(
        int $evaluationId
    ): array {
        $evaluation = Evaluation::findOrFail($evaluationId);
        $analytics = $this->analytics($evaluationId);
        $schoolAverage = $this->schoolAverageByKriteria(
            $evaluation->period_id,
            $evaluation->id
        );

        $comparison = [];

        foreach ($analytics as $item) {
            $guruScore = $this->normalizeScore(
                $item['average']
            );

            $schoolScore = $schoolAverage[$item['kriteria']]
                ?? null;

            $gap = $schoolScore === null
                ? null
                : round($guruScore - $schoolScore, 2);

            $comparison[] = [
                'kriteria' => $item['kriteria'],
                'guru_score' => $guruScore,
                'school_average' => $schoolScore,
                'gap' => $gap,
                'status' => $gap === null
                    ? 'no-benchmark'
                    : ($gap >= 5
                        ? 'above'
                        : ($gap <= -5
                            ? 'below'
                            : 'balanced')),
            ];
        }

        return $comparison;
    }

    public function generateRecommendations(
        int $evaluationId
    ): array {
        $evaluation = Evaluation::findOrFail($evaluationId);
        $analytics = $this->analytics($evaluationId);
        $comparison = $this->compareEvaluationToSchool(
            $evaluationId
        );

        $comparisonByKriteria = [];

        foreach ($comparison as $item) {
            $comparisonByKriteria[$item['kriteria']] = $item;
        }

        $priorityItems = [];

        foreach ($analytics as $item) {
            $score = $this->normalizeScore(
                $item['average']
            );

            $rule = $this->resolveRecommendationRule(
                $item['kriteria']
            );

            if (!$rule || $score >= $rule['threshold']) {
                continue;
            }

            $priorityItems[] = [
                'kriteria' => $item['kriteria'],
                'score' => $score,
                'threshold' => $rule['threshold'],
                'insight' => $rule['insight'],
                'recommendations' => $rule['recommendations'],
                'benchmark' => $comparisonByKriteria[$item['kriteria']]
                    ?? null,
            ];
        }

        usort($priorityItems, function ($a, $b) {
            return $a['score'] <=> $b['score'];
        });

        $strength = $this->bestAndWorstCompetency($analytics);
        $text = $this->buildRecommendationText(
            $priorityItems,
            $strength
        );

        return [
            'items' => $priorityItems,
            'text' => $text,
            'strength' => $strength['best'],
            'focus' => $priorityItems[0] ?? null,
        ];
    }

    private function resolveRecommendationRule(
        string $kriteriaName
    ): ?array {
        $name = Str::lower($kriteriaName);

        $rules = [
            'pedagogik' => [
                'threshold' => 75,
                'insight' => 'Kompetensi pedagogik Anda masih perlu diperkuat.',
                'recommendations' => [
                    'Mengikuti workshop manajemen kelas.',
                    'Menggunakan metode pembelajaran aktif di kelas.',
                    'Menambah variasi media ajar dan asesmen formatif.',
                ],
            ],
            'profesional' => [
                'threshold' => 70,
                'insight' => 'Penguasaan profesional perlu ditingkatkan agar materi lebih mendalam.',
                'recommendations' => [
                    'Mengikuti pelatihan pendalaman materi bidang studi.',
                    'Menyusun bank soal dan modul ajar berbasis capaian belajar.',
                    'Melakukan lesson study atau peer review dengan guru mapel sejenis.',
                ],
            ],
            'kepribadian' => [
                'threshold' => 75,
                'insight' => 'Aspek kepribadian masih bisa diperkuat untuk menjaga konsistensi profesional.',
                'recommendations' => [
                    'Menyusun target disiplin pribadi mingguan.',
                    'Meminta umpan balik berkala dari kepala sekolah atau rekan sejawat.',
                    'Membangun jurnal refleksi setelah proses pembelajaran.',
                ],
            ],
            'sosial' => [
                'threshold' => 75,
                'insight' => 'Kompetensi sosial perlu dikuatkan agar kolaborasi dan komunikasi lebih efektif.',
                'recommendations' => [
                    'Meningkatkan komunikasi aktif dengan siswa dan orang tua.',
                    'Terlibat dalam forum kolaborasi guru di sekolah.',
                    'Melatih teknik komunikasi empatik dan umpan balik positif.',
                ],
            ],
        ];

        foreach ($rules as $keyword => $rule) {
            if (Str::contains($name, $keyword)) {
                return $rule;
            }
        }

        return null;
    }

    private function buildRecommendationText(
        array $priorityItems,
        array $strength
    ): string {
        if (empty($priorityItems)) {
            $best = $strength['best']['kompetensi'] ?? 'beberapa kompetensi utama';

            return trim(
                "Performa Anda sudah berada pada level yang baik.\n" .
                "Fokuskan pengembangan lanjutan pada penguatan kompetensi terbaik seperti {$best} dan berbagi praktik baik dengan rekan guru lain."
            );
        }

        $lines = [];

        foreach ($priorityItems as $item) {
            $lines[] = $item['kriteria'] . ' Anda masih rendah (' . $item['score'] . ').';
            $lines[] = 'Rekomendasi:';

            foreach ($item['recommendations'] as $recommendation) {
                $lines[] = '- ' . $recommendation;
            }

            $lines[] = '';
        }

        return trim(implode("\n", $lines));
    }
}
