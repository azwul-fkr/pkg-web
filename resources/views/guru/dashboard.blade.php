@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="page-stack">
        @if (!$guru)
            <div class="page-card p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                        <i data-lucide="alert-circle" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold tracking-tight text-slate-900">Profil guru belum tersedia</h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Akun ini sudah login sebagai guru, tetapi belum terhubung dengan data guru.
                            Silakan hubungi admin agar profil guru dibuat di menu Data Guru.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="grid gap-5 xl:grid-cols-[1.35fr_.85fr]">
                <div class="page-card overflow-hidden">
                    <div class="border-b border-slate-200/80 px-5 py-5 sm:px-6">
                        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                            <div class="space-y-4">
                                <div class="inline-flex items-center gap-2 rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[.18em] text-cyan-700 ring-1 ring-cyan-100">
                                    Dashboard Guru
                                </div>

                                <div>
                                    <h3 class="text-2xl font-semibold tracking-tight text-slate-900">
                                        {{ $guru->user?->name ?? auth()->user()->name }}
                                    </h3>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                        Mata Pelajaran: <span class="font-medium text-slate-700">{{ $guru->subject ?? '-' }}</span>
                                        <span class="mx-2 text-slate-300">|</span>
                                        Periode aktif: <span class="font-medium text-slate-700">{{ $period?->name ?? '-' }}</span>
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span class="badge badge-info">Guru</span>
                                    <span class="badge badge-success">{{ $approvedEvidence }} approved</span>
                                    <span class="badge badge-warning">{{ $pendingEvidence }} pending</span>
                                    <span class="badge badge-danger">{{ $rejectedEvidence }} rejected</span>
                                </div>
                            </div>

                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-lg shadow-cyan-900/15">
                                <i data-lucide="layout-dashboard" class="h-6 w-6"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 p-5 sm:grid-cols-3 sm:p-6">
                        <div class="rounded-2xl border border-slate-200/80 bg-slate-50/80 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[.16em] text-slate-500">Evidence</p>
                            <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ $totalEvidence }}</p>
                            <p class="mt-1 text-sm text-slate-500">Dokumen yang sudah diunggah.</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-700">Approved</p>
                            <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ $approvedEvidence }}</p>
                            <p class="mt-1 text-sm text-slate-500">Evidence yang sudah divalidasi.</p>
                        </div>
                        <div class="rounded-2xl border border-amber-100 bg-amber-50/70 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[.16em] text-amber-700">Pending</p>
                            <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ $pendingEvidence }}</p>
                            <p class="mt-1 text-sm text-slate-500">Masih menunggu pemeriksaan.</p>
                        </div>
                    </div>
                </div>

                <div class="page-card p-5 sm:p-6">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold tracking-tight text-slate-900">Refleksi Guru</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Ringkasan hasil evaluasi terakhir dan arah perbaikan.</p>
                        </div>
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600 ring-1 ring-cyan-100">
                            <i data-lucide="book-open-check" class="h-6 w-6"></i>
                        </div>
                    </div>

                    @if ($reflection)
                        <div class="space-y-4">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-400">Periode Evaluasi</p>
                                <p class="mt-2 text-sm font-semibold text-slate-800">{{ $reflection->evaluation->period->name ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700">Refleksi</p>
                                <div class="mt-2 rounded-2xl border border-cyan-100 bg-cyan-50/70 p-4 text-sm leading-6 text-slate-700">
                                    {{ $reflection->reflection }}
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700">Rencana Perbaikan</p>
                                <div class="mt-2 rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4 text-sm leading-6 text-slate-700">
                                    {{ $reflection->improvement_plan }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50/70 p-6 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <i data-lucide="notebook-pen" class="h-6 w-6"></i>
                            </div>
                            <h4 class="mt-4 text-sm font-semibold text-slate-800">Belum ada refleksi</h4>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Refleksi guru akan muncul setelah evaluasi selesai.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="page-card p-4 sm:p-5">
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-slate-500">Total Evidence</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $totalEvidence }}</p>
                </div>
                <div class="page-card p-4 sm:p-5">
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-700">Approved</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $approvedEvidence }}</p>
                </div>
                <div class="page-card p-4 sm:p-5">
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-amber-700">Pending</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $pendingEvidence }}</p>
                </div>
                <div class="page-card p-4 sm:p-5">
                    <p class="text-xs font-semibold uppercase tracking-[.16em] text-red-700">Rejected</p>
                    <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $rejectedEvidence }}</p>
                </div>
            </div>

            @if ($evaluation)
                <div class="grid gap-5 xl:grid-cols-[1.05fr_.95fr]">
                    <div class="page-card p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-500">Hasil Penilaian Terakhir</p>
                                <p class="mt-2 text-4xl font-semibold tracking-tight text-slate-900">{{ $finalScore }}</p>
                            </div>
                            <span class="badge badge-info w-fit">{{ strtoupper($evaluation->status) }}</span>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-base font-semibold tracking-tight text-slate-900">Tren Skor Penilaian</h3>
                            <p class="mt-1 text-sm text-slate-500">Perkembangan skor final pada beberapa evaluasi terakhir.</p>

                            <div class="mt-4">
                                @if (!empty($trendLabels) && count($trendLabels) > 0)
                                    <div class="h-72">
                                        <canvas id="trendChart"></canvas>
                                    </div>
                                @else
                                    <div class="empty-state">Belum ada data tren untuk ditampilkan.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="page-card p-5 sm:p-6">
                        <h3 class="text-base font-semibold tracking-tight text-slate-900">Analitik Perbandingan</h3>
                        <p class="mt-1 text-sm text-slate-500">Perbandingan performa Anda dengan rata-rata sekolah.</p>

                        <div class="mt-4">
                            @if (!empty($competencyComparisonChart['labels']))
                                <div class="h-72">
                                    <canvas id="comparisonChart"></canvas>
                                </div>
                            @else
                                <div class="empty-state">Benchmark sekolah belum tersedia.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    <div class="page-card p-5 sm:p-6">
                        <p class="text-xs font-semibold uppercase tracking-[.18em] text-emerald-700">Kompetensi Terbaik</p>
                        @if ($bestWorst['best'])
                            <h3 class="mt-3 text-xl font-semibold tracking-tight text-slate-900">{{ $bestWorst['best']['kode'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600 line-clamp-2">{{ $bestWorst['best']['kompetensi'] }}</p>
                            <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ $bestWorst['best']['average'] }}</p>
                        @else
                            <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                        @endif
                    </div>

                    <div class="page-card p-5 sm:p-6">
                        <p class="text-xs font-semibold uppercase tracking-[.18em] text-red-700">Kompetensi Terlemah</p>
                        @if ($bestWorst['worst'])
                            <h3 class="mt-3 text-xl font-semibold tracking-tight text-slate-900">{{ $bestWorst['worst']['kode'] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600 line-clamp-2">{{ $bestWorst['worst']['kompetensi'] }}</p>
                            <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ $bestWorst['worst']['average'] }}</p>
                        @else
                            <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                        @endif
                    </div>
                </div>

                <div class="grid gap-5 xl:grid-cols-2">
                    <div class="page-card p-5 sm:p-6">
                        <h3 class="text-base font-semibold tracking-tight text-slate-900">Feedback Penilai</h3>
                        <p class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                            {{ $evaluation->feedback ?? 'Belum ada feedback' }}
                        </p>
                    </div>

                    <div class="page-card p-5 sm:p-6">
                        <h3 class="text-base font-semibold tracking-tight text-slate-900">Rekomendasi Pengembangan</h3>
                        <p class="mt-4 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-700 whitespace-pre-line">
                            {{ $evaluation->recommendation ?? 'Belum ada rekomendasi' }}
                        </p>
                    </div>
                </div>

                <div class="page-card p-5 sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold tracking-tight text-slate-900">Rekomendasi Prioritas</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Sistem menyusun prioritas pengembangan dari hasil evaluasi terakhir.</p>
                        </div>
                        <span class="badge badge-warning">Otomatis</span>
                    </div>

                    @if (!empty($recommendationEngine['items']))
                        <div class="mt-5 space-y-4">
                            @foreach ($recommendationEngine['items'] as $item)
                                <div class="rounded-3xl border border-amber-100 bg-amber-50/80 p-4 sm:p-5">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $item['kriteria'] }}</p>
                                            <p class="mt-1 text-sm leading-6 text-slate-600">{{ $item['insight'] }}</p>
                                        </div>
                                        <div class="text-sm font-semibold text-amber-700">
                                            {{ $item['score'] }}
                                        </div>
                                    </div>

                                    <div class="mt-3 text-xs text-slate-600">
                                        @if (!empty($item['benchmark']['school_average']))
                                            Rata-rata sekolah: {{ $item['benchmark']['school_average'] }}
                                        @endif
                                    </div>

                                    <ul class="mt-4 space-y-2 text-sm leading-6 text-slate-700">
                                        @foreach ($item['recommendations'] as $recommendation)
                                            <li class="flex gap-2">
                                                <span class="mt-2 h-2 w-2 rounded-full bg-cyan-500"></span>
                                                <span>{{ $recommendation }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-5 rounded-3xl border border-emerald-100 bg-emerald-50/80 p-4 text-sm leading-6 text-emerald-800">
                            Tidak ada area kritis di bawah ambang batas. Pertahankan performa saat ini dan lanjutkan berbagi praktik baik.
                        </div>
                    @endif
                </div>

                @if (!empty($schoolComparison))
                    <div class="page-card">
                        <div class="page-card-header">
                            <div>
                                <h3 class="page-card-title">Saya vs Rata-rata Sekolah</h3>
                                <p class="page-card-subtitle">Perbandingan performa guru pada setiap kriteria utama.</p>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="app-table text-xs sm:text-sm">
                                <thead>
                                    <tr>
                                        <th>Kriteria</th>
                                        <th class="text-center">Saya</th>
                                        <th class="text-center">Rata-rata Sekolah</th>
                                        <th class="text-center">Gap</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schoolComparison as $comparison)
                                        <tr>
                                            <td class="font-semibold">{{ $comparison['kriteria'] }}</td>
                                            <td class="text-center">{{ $comparison['guru_score'] }}</td>
                                            <td class="text-center">{{ $comparison['school_average'] ?? '-' }}</td>
                                            <td class="text-center">{{ $comparison['gap'] ?? '-' }}</td>
                                            <td>
                                                <span class="badge text-xs {{ $comparison['status'] === 'above' ? 'badge-success' : ($comparison['status'] === 'below' ? 'badge-danger' : 'badge-info') }}">
                                                    {{ $comparison['status'] === 'above' ? 'Di atas rata-rata' : ($comparison['status'] === 'below' ? 'Di bawah rata-rata' : 'Seimbang') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="page-card">
                    <div class="page-card-header">
                        <div>
                            <h3 class="page-card-title">Refleksi Diri Guru</h3>
                            <p class="page-card-subtitle">Catat refleksi dan rencana perbaikan setelah menerima hasil.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('guru.reflection.store', $evaluation->id) }}" class="space-y-4 p-4 sm:p-5">
                        @csrf

                        <div>
                            <label class="form-label text-xs sm:text-sm">Refleksi Diri</label>
                            <textarea name="reflection" rows="4" class="form-control text-xs sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label class="form-label text-xs sm:text-sm">Rencana Perbaikan</label>
                            <textarea name="improvement_plan" rows="4" class="form-control text-xs sm:text-sm"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-xs sm:text-sm">Simpan Refleksi</button>
                        </div>
                    </form>
                </div>

                @if ($gapAnalysis)
                    <div class="page-card">
                        <div class="page-card-header">
                            <div>
                                <h3 class="page-card-title">Gap Analysis</h3>
                                <p class="page-card-subtitle">Perbandingan self assessment dengan hasil penilai.</p>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="app-table text-xs sm:text-sm">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">Kode</th>
                                        <th class="whitespace-nowrap">Kompetensi</th>
                                        <th class="whitespace-nowrap text-center">Self</th>
                                        <th class="whitespace-nowrap text-center">Penilai</th>
                                        <th class="whitespace-nowrap text-center">Gap</th>
                                        <th class="whitespace-nowrap">Interpretasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gapAnalysis as $gap)
                                        <tr>
                                            <td class="font-semibold whitespace-nowrap">{{ $gap['kode'] }}</td>
                                            <td class="max-w-xs truncate">{{ $gap['kompetensi'] }}</td>
                                            <td class="text-center">{{ $gap['self_avg'] }}</td>
                                            <td class="text-center">{{ $gap['penilai_avg'] }}</td>
                                            <td class="text-center">
                                                <span class="badge text-xs {{ $gap['gap'] > 0 ? 'badge-danger' : ($gap['gap'] < 0 ? 'badge-success' : 'badge-info') }}">
                                                    {{ $gap['gap'] }}
                                                </span>
                                            </td>
                                            <td class="text-xs sm:text-sm">
                                                @if ($gap['gap'] >= 1)
                                                    Overestimate
                                                @elseif($gap['gap'] <= -1)
                                                    Underestimate
                                                @else
                                                    Objektif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @elseif ($pendingEvaluation)
                <div class="page-card p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-700 ring-1 ring-amber-100">
                            <i data-lucide="clock" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold tracking-tight text-slate-900">Hasil penilaian belum difinalisasi</h3>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                                Status saat ini: <span class="font-semibold uppercase">{{ $pendingEvaluation->status }}</span>.
                                Hasil akan tampil setelah admin melakukan approve di menu Monitoring.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-state">Belum ada hasil penilaian finalized.</div>
            @endif
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const labels = {!! json_encode($trendLabels ?? []) !!};
            const data = {!! json_encode($trendScores ?? []) !!};
            const comparisonLabels = {!! json_encode($competencyComparisonChart['labels'] ?? []) !!};
            const guruScores = {!! json_encode($competencyComparisonChart['guruScores'] ?? []) !!};
            const schoolScores = {!! json_encode($competencyComparisonChart['schoolScores'] ?? []) !!};

            if (labels && labels.length) {
                const trendCanvas = document.getElementById('trendChart');

                if (trendCanvas) {
                    const ctx = trendCanvas.getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Skor Final',
                                data: data,
                                borderColor: '#06b6d4',
                                backgroundColor: 'rgba(6,182,212,0.08)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 4,
                                pointBackgroundColor: '#06b6d4',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: 100
                                }
                            },
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            }

            if (comparisonLabels.length) {
                const comparisonCanvas = document.getElementById('comparisonChart');

                if (comparisonCanvas) {
                    const comparisonCtx = comparisonCanvas.getContext('2d');

                    new Chart(comparisonCtx, {
                        type: 'bar',
                        data: {
                            labels: comparisonLabels,
                            datasets: [{
                                label: 'Saya',
                                data: guruScores,
                                backgroundColor: '#06b6d4',
                                borderRadius: 10,
                            }, {
                                label: 'Rata-rata Sekolah',
                                data: schoolScores,
                                backgroundColor: '#cbd5e1',
                                borderRadius: 10,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: 100
                                }
                            }
                        }
                    });
                }
            }
        })();
    </script>
@endpush
