@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="mb-6">

        <h1 class="
        text-3xl
        font-bold
        text-slate-800
    ">
            Dashboard Admin
        </h1>

        <p class="
        text-slate-500
        mt-1
    ">
            Monitoring sistem penilaian kinerja guru
        </p>

    </div>

    {{-- ================= KPI CARD ================= --}}
    <div class="
    grid
    grid-cols-1
    md:grid-cols-2
    xl:grid-cols-4
    gap-6
    mb-8
">

        {{-- TOTAL GURU --}}
        <div
            class="
        bg-white
        rounded-2xl
        shadow-sm
        p-6
        border
        border-slate-100
    ">

            <div class="
            flex
            items-center
            justify-between
        ">

                <div>

                    <p class="
                    text-sm
                    text-slate-500
                ">
                        Total Guru
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-slate-800
                ">
                        {{ $totalGuru ?? 0 }}
                    </h1>

                </div>

                <div
                    class="
                w-14
                h-14
                rounded-2xl
                bg-blue-100
                flex
                items-center
                justify-center
            ">

                    <i data-lucide="users"
                        class="
                        w-7
                        h-7
                        text-blue-600
                    "></i>

                </div>

            </div>

        </div>

        {{-- TOTAL PENILAI --}}
        <div
            class="
        bg-white
        rounded-2xl
        shadow-sm
        p-6
        border
        border-slate-100
    ">

            <div class="
            flex
            items-center
            justify-between
        ">

                <div>

                    <p class="
                    text-sm
                    text-slate-500
                ">
                        Total Penilai
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-slate-800
                ">
                        {{ $totalPenilai ?? 0 }}
                    </h1>

                </div>

                <div
                    class="
                w-14
                h-14
                rounded-2xl
                bg-green-100
                flex
                items-center
                justify-center
            ">

                    <i data-lucide="user-check"
                        class="
                        w-7
                        h-7
                        text-green-600
                    "></i>

                </div>

            </div>

        </div>

        {{-- TOTAL EVIDENCE --}}
        <div
            class="
        bg-white
        rounded-2xl
        shadow-sm
        p-6
        border
        border-slate-100
    ">

            <div class="
            flex
            items-center
            justify-between
        ">

                <div>

                    <p class="
                    text-sm
                    text-slate-500
                ">
                        Total Evidence
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-slate-800
                ">
                        {{ $totalEvidence ?? 0 }}
                    </h1>

                </div>

                <div
                    class="
                w-14
                h-14
                rounded-2xl
                bg-orange-100
                flex
                items-center
                justify-center
            ">

                    <i data-lucide="folder-open"
                        class="
                        w-7
                        h-7
                        text-orange-600
                    "></i>

                </div>

            </div>

        </div>

        {{-- TOTAL PENILAIAN --}}
        <div
            class="
        bg-white
        rounded-2xl
        shadow-sm
        p-6
        border
        border-slate-100
    ">

            <div class="
            flex
            items-center
            justify-between
        ">

                <div>

                    <p class="
                    text-sm
                    text-slate-500
                ">
                        Total Penilaian
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-slate-800
                ">
                        {{ $totalEvaluation ?? 0 }}
                    </h1>

                </div>

                <div
                    class="
                w-14
                h-14
                rounded-2xl
                bg-purple-100
                flex
                items-center
                justify-center
            ">

                    <i data-lucide="clipboard-check"
                        class="
                        w-7
                        h-7
                        text-purple-600
                    "></i>

                </div>

            </div>

        </div>

    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Performa Periode</h2>
                    <p class="text-sm text-slate-500 mt-1">Rata-rata skor final guru per periode.</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="periodPerformanceChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Status Evaluasi</h2>
                    <p class="text-sm text-slate-500 mt-1">Distribusi progres penilaian saat ini.</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="evaluationStatusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= SECOND SECTION ================= --}}
    <div class="
    grid
    grid-cols-1
    xl:grid-cols-3
    gap-6
    mb-8
">

        {{-- STATUS EVIDENCE --}}
        <div
            class="
        xl:col-span-2
        bg-white
        rounded-2xl
        shadow-sm
        border
        border-slate-100
        p-6
    ">

            <div class="
            flex
            items-center
            justify-between
            mb-6
        ">

                <h2 class="
                text-lg
                font-bold
                text-slate-800
            ">
                    Statistik Evidence
                </h2>

            </div>

            <div class="
            grid
            grid-cols-1
            md:grid-cols-3
            gap-4
        ">

                {{-- APPROVED --}}
                <div
                    class="
                bg-green-50
                rounded-2xl
                p-5
                border
                border-green-100
            ">

                    <p class="
                    text-sm
                    text-green-700
                ">
                        Approved
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-green-600
                ">
                        {{ $approvedEvidence ?? 0 }}
                    </h1>

                </div>

                {{-- PENDING --}}
                <div
                    class="
                bg-yellow-50
                rounded-2xl
                p-5
                border
                border-yellow-100
            ">

                    <p class="
                    text-sm
                    text-yellow-700
                ">
                        Pending
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-yellow-600
                ">
                        {{ $pendingEvidence ?? 0 }}
                    </h1>

                </div>

                {{-- REJECTED --}}
                <div
                    class="
                bg-red-50
                rounded-2xl
                p-5
                border
                border-red-100
            ">

                    <p class="
                    text-sm
                    text-red-700
                ">
                        Rejected
                    </p>

                    <h1
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-red-600
                ">
                        {{ $rejectedEvidence ?? 0 }}
                    </h1>

                </div>

            </div>

        </div>

        {{-- PERIODE AKTIF --}}
        <div
            class="
        bg-white
        rounded-2xl
        shadow-sm
        border
        border-slate-100
        p-6
    ">

            <div class="
            flex
            items-center
            justify-between
            mb-6
        ">

                <h2 class="
                text-lg
                font-bold
                text-slate-800
            ">
                    Periode Aktif
                </h2>

                <i data-lucide="calendar-range"
                    class="
                    w-5
                    h-5
                    text-blue-600
                "></i>

            </div>

            @if ($activePeriod)
                <div
                    class="
                bg-blue-50
                rounded-2xl
                p-5
                border
                border-blue-100
            ">

                    <h3
                        class="
                    text-xl
                    font-bold
                    text-blue-700
                ">
                        {{ $activePeriod->name }}
                    </h3>

                    <p
                        class="
                    text-sm
                    text-slate-500
                    mt-2
                ">
                        {{ $activePeriod->start_date }}
                        -
                        {{ $activePeriod->end_date }}
                    </p>

                    <div
                        class="
                    mt-4
                    inline-flex
                    items-center
                    px-3
                    py-1
                    rounded-full
                    bg-green-100
                    text-green-700
                    text-xs
                    font-semibold
                ">
                        ACTIVE
                    </div>

                </div>
            @else
                <div
                    class="
                bg-slate-50
                rounded-2xl
                p-5
                text-center
                text-slate-500
            ">

                    Belum ada periode aktif

                </div>
            @endif

        </div>

    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Top Guru Performance</h2>
                    <p class="text-sm text-slate-500 mt-1">Lima guru dengan skor akhir tertinggi.</p>
                </div>
            </div>
            <div class="h-80">
                <canvas id="topTeachersChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Rata-rata Sekolah</h2>
                    <p class="text-sm text-slate-500 mt-1">Benchmark kompetensi pada periode aktif.</p>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($schoolAverageByKriteria as $kriteria => $score)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-slate-700">{{ $kriteria }}</span>
                            <span class="font-bold text-slate-900">{{ $score }}</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-blue-500"
                                style="width: {{ min(100, max(0, $score)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-500">
                        Belum ada data evaluasi finalized untuk menghitung benchmark sekolah.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ================= RANKING GURU ================= --}}
    <div class="
    bg-white
    rounded-2xl
    shadow-sm
    border
    border-slate-100
    p-6
">

        <div class="
        flex
        items-center
        justify-between
        mb-6
    ">

            <div>

                <h2 class="
                text-xl
                font-bold
                text-slate-800
            ">
                    Ranking Guru
                </h2>

                <p class="
                text-sm
                text-slate-500
                mt-1
            ">
                    Berdasarkan final score penilaian
                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="
            w-full
            border-separate
            border-spacing-y-2
        ">

                <thead>

                    <tr
                        class="
                    text-left
                    text-slate-500
                    text-sm
                ">

                        <th class="pb-3">#</th>
                        <th class="pb-3">Guru</th>
                        <th class="pb-3">Mata Pelajaran</th>
                        <th class="pb-3">Final Score</th>
                        <th class="pb-3">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($rankingGuru ?? [] as $item)
                        <tr
                            class="
                        bg-slate-50
                        hover:bg-slate-100
                        transition
                    ">

                            <td
                                class="
                            p-4
                            rounded-l-2xl
                            font-bold
                        ">
                                #{{ $loop->iteration }}
                            </td>

                            <td class="p-4">

                                <div
                                    class="
                                flex
                                items-center
                                gap-3
                            ">

                                    <div
                                        class="
                                    w-10
                                    h-10
                                    rounded-full
                                    bg-blue-600
                                    flex
                                    items-center
                                    justify-center
                                    text-white
                                    font-bold
                                ">

                                        {{ strtoupper(substr($item['guru']->user->name, 0, 1)) }}

                                    </div>

                                    <div>

                                        <h4
                                            class="
                                        font-semibold
                                        text-slate-800
                                    ">
                                            {{ $item['guru']->user->name }}
                                        </h4>

                                    </div>

                                </div>

                            </td>

                            <td class="p-4">
                                {{ $item['guru']->subject }}
                            </td>

                            <td
                                class="
                            p-4
                            font-bold
                            text-blue-600
                        ">
                                {{ $item['score'] }}
                            </td>

                            <td
                                class="
                            p-4
                            rounded-r-2xl
                        ">

                                <span
                                    class="
                                px-3
                                py-1
                                rounded-full
                                text-xs
                                font-semibold
                                bg-green-100
                                text-green-700
                            ">

                                    Finalized

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="
                                text-center
                                py-10
                                text-slate-400
                            ">

                                Belum ada data ranking guru

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const periodLabels = {!! json_encode($periodPerformance['labels'] ?? []) !!};
            const periodScores = {!! json_encode($periodPerformance['scores'] ?? []) !!};
            const statusCounts = {!! json_encode(array_values($evaluationStatusCounts ?? [])) !!};
            const topLabels = {!! json_encode($topTeachersChart['labels'] ?? []) !!};
            const topScores = {!! json_encode($topTeachersChart['scores'] ?? []) !!};

            if (periodLabels.length) {
                new Chart(document.getElementById('periodPerformanceChart'), {
                    type: 'line',
                    data: {
                        labels: periodLabels,
                        datasets: [{
                            label: 'Rata-rata skor',
                            data: periodScores,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(14,165,233,0.15)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            new Chart(document.getElementById('evaluationStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Draft', 'Submitted', 'Revised', 'Finalized'],
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: ['#f59e0b', '#06b6d4', '#ef4444', '#22c55e'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            if (topLabels.length) {
                new Chart(document.getElementById('topTeachersChart'), {
                    type: 'bar',
                    data: {
                        labels: topLabels,
                        datasets: [{
                            label: 'Final score',
                            data: topScores,
                            backgroundColor: ['#0ea5e9', '#06b6d4', '#14b8a6', '#22c55e', '#84cc16'],
                            borderRadius: 12,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endpush
