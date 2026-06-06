@extends('layouts.app')

@section('title', 'Dashboard Penilai')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Dashboard Penilai</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Periode aktif: {{ $activePeriod?->name ?? 'Belum tersedia' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:flex">
                    <a href="{{ route('penilai.guru.index') }}" class="btn-primary text-center text-sm">Daftar Guru</a>
                    <a href="{{ route('penilai.hasil') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Hasil Penilaian</a>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-slate-500">Total Assignment</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $assignments->count() }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-amber-600">Draft</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statusCounts['draft'] ?? 0 }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-cyan-600">Submitted</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statusCounts['submitted'] ?? 0 }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-emerald-600">Finalized</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $statusCounts['finalized'] ?? 0 }}</p>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-3">
            <div class="page-card p-5 xl:col-span-1">
                <h3 class="text-base font-bold text-slate-900">Status Progress</h3>
                <p class="mt-1 text-xs text-slate-500">Distribusi evaluasi yang sedang Anda tangani.</p>
                <div class="mt-4 h-72">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div class="page-card p-5 xl:col-span-2">
                <h3 class="text-base font-bold text-slate-900">Skor Guru yang Ditangani</h3>
                <p class="mt-1 text-xs text-slate-500">Ringkasan performa guru berdasarkan evaluasi yang sudah dikirim.</p>
                <div class="mt-4 h-72">
                    <canvas id="teacherPerformanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="page-card p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Ringkasan Evaluasi</h3>
                    <p class="mt-1 text-xs text-slate-500">Monitoring cepat untuk guru yang menjadi tanggung jawab Anda.</p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="app-table text-sm">
                    <thead>
                        <tr>
                            <th>Guru</th>
                            <th>Status</th>
                            <th class="text-center">Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teacherPerformance as $item)
                            <tr>
                                <td class="font-semibold">{{ $item['guru'] }}</td>
                                <td>
                                    <span class="badge text-xs {{ $item['status'] === 'finalized' ? 'badge-success' : 'badge-info' }}">
                                        {{ strtoupper($item['status']) }}
                                    </span>
                                </td>
                                <td class="text-center font-bold text-slate-900">{{ $item['score'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-500">
                                    Belum ada evaluasi yang dikirim pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (() => {
            const statusCounts = {!! json_encode([
                $statusCounts['belum_mulai'] ?? 0,
                $statusCounts['draft'] ?? 0,
                $statusCounts['submitted'] ?? 0,
                $statusCounts['revised'] ?? 0,
                $statusCounts['finalized'] ?? 0,
            ]) !!};
            const teacherLabels = {!! json_encode($teacherPerformanceChart['labels'] ?? []) !!};
            const teacherScores = {!! json_encode($teacherPerformanceChart['scores'] ?? []) !!};

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Belum Mulai', 'Draft', 'Submitted', 'Revised', 'Finalized'],
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: ['#cbd5e1', '#f59e0b', '#06b6d4', '#ef4444', '#22c55e'],
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

            if (teacherLabels.length) {
                new Chart(document.getElementById('teacherPerformanceChart'), {
                    type: 'bar',
                    data: {
                        labels: teacherLabels,
                        datasets: [{
                            label: 'Final score',
                            data: teacherScores,
                            backgroundColor: '#06b6d4',
                            borderRadius: 10,
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
