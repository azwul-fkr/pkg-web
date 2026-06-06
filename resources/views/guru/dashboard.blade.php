@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="page-stack">
        @if (!$guru)
            <div class="page-card p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                        <i data-lucide="alert-circle" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Profil guru belum tersedia</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Akun ini sudah login sebagai guru, tetapi belum terhubung dengan data guru.
                            Silakan hubungi admin agar profil guru dibuat di menu Data Guru.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="page-card p-3 sm:p-5">
                <div class="flex flex-col gap-2 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">{{ $guru->user?->name ?? auth()->user()->name }}</h3>
                        <p class="mt-1 text-xs sm:text-sm text-slate-500 line-clamp-2">
                            Mata Pelajaran: {{ $guru->subject ?? '-' }} | Periode Aktif: {{ $period?->name ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- =====================================
    REFLEKSI GURU
===================================== --}}
            <div class="page-card p-3 sm:p-5">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 sm:mb-5">

                    <div>

                        <h3 class="text-base sm:text-lg font-bold text-slate-800">

                            Refleksi Guru

                        </h3>

                        <p class="text-xs sm:text-sm text-slate-500 mt-1">

                            Refleksi hasil evaluasi terakhir.

                        </p>

                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-cyan-100 text-cyan-600 flex items-center justify-center flex-shrink-0">

                        <i data-lucide="book-open-check" class="w-6 h-6"></i>

                    </div>

                </div>

                @if ($reflection)
                    <div class="space-y-3 sm:space-y-5">

                        {{-- PERIODE --}}
                        <div class="rounded-2xl bg-slate-50 p-3 sm:p-4 border border-slate-200">

                            <p class="text-xs uppercase tracking-wide text-slate-400 mb-1">

                                Periode Evaluasi

                            </p>

                            <h4 class="font-semibold text-sm sm:text-base text-slate-800">

                                {{ $reflection->evaluation->period->name ?? '-' }}

                            </h4>

                        </div>

                        {{-- REFLECTION --}}
                        <div>

                            <h4 class="font-semibold text-sm text-slate-700 mb-2">

                                Refleksi

                            </h4>

                            <div class="rounded-2xl bg-blue-50 border border-blue-100 p-3 sm:p-4 text-xs sm:text-sm text-slate-700 leading-relaxed">

                                {{ $reflection->reflection }}

                            </div>

                        </div>

                        {{-- IMPROVEMENT --}}
                        <div>

                            <h4 class="font-semibold text-sm text-slate-700 mb-2">

                                Rencana Perbaikan

                            </h4>

                            <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-3 sm:p-4 text-xs sm:text-sm text-slate-700 leading-relaxed">

                                {{ $reflection->improvement_plan }}

                            </div>

                        </div>

                    </div>
                @else
                    <div class="text-center py-6 sm:py-10">

                        <div class="w-12 sm:w-16 h-12 sm:h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-3 sm:mb-4">

                            <i data-lucide="notebook-pen" class="w-5 sm:w-7 h-5 sm:h-7 text-slate-400"></i>

                        </div>

                        <h4 class="font-semibold text-slate-700 text-sm sm:text-base">

                            Belum Ada Refleksi

                        </h4>

                        <p class="text-xs sm:text-sm text-slate-500 mt-2">

                            Refleksi guru akan muncul setelah evaluasi selesai.

                        </p>

                    </div>
                @endif

            </div>

            <div class="grid gap-3 sm:gap-4 grid-cols-2 sm:grid-cols-2 lg:grid-cols-4">
                <div class="page-card p-3 sm:p-5">
                    <p class="text-xs sm:text-sm font-semibold text-slate-500">Total Evidence</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">{{ $totalEvidence }}</p>
                </div>
                <div class="page-card p-3 sm:p-5">
                    <p class="text-xs sm:text-sm font-semibold text-emerald-600">Approved</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">{{ $approvedEvidence }}</p>
                </div>
                <div class="page-card p-3 sm:p-5">
                    <p class="text-xs sm:text-sm font-semibold text-amber-600">Pending</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">{{ $pendingEvidence }}</p>
                </div>
                <div class="page-card p-3 sm:p-5">
                    <p class="text-xs sm:text-sm font-semibold text-red-600">Rejected</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">{{ $rejectedEvidence }}</p>
                </div>
            </div>

            @if ($evaluation)
                <div class="page-card p-3 sm:p-5">
                    <div class="flex flex-col gap-3 sm:gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-semibold text-slate-500">Hasil Penilaian Terakhir</p>
                            <p class="mt-1 text-2xl sm:text-3xl font-bold text-slate-900">{{ $finalScore }}</p>
                        </div>
                        <span class="badge badge-info w-fit">{{ strtoupper($evaluation->status) }}</span>
                    </div>
                </div>

                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Tren Skor Penilaian</h3>
                    <p class="text-xs text-slate-500 mt-1">Perkembangan skor final pada beberapa evaluasi terakhir.</p>

                    <div class="mt-3">
                        @if(!empty($trendLabels) && count($trendLabels) > 0)
                            <canvas id="trendChart" height="120"></canvas>
                        @else
                            <div class="text-center py-6 sm:py-10 text-sm text-slate-500">Belum ada data tren untuk ditampilkan.</div>
                        @endif
                    </div>
                </div>

                <div class="page-card p-3 sm:p-5">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900">Comparative Analytics</h3>
                    <p class="text-xs text-slate-500 mt-1">Perbandingan performa Anda dengan rata-rata sekolah.</p>

                    <div class="mt-3">
                        @if(!empty($competencyComparisonChart['labels']))
                            <div class="h-80">
                                <canvas id="comparisonChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-6 sm:py-10 text-sm text-slate-500">Benchmark sekolah belum tersedia.</div>
                        @endif
                    </div>
                </div>

                <div class="grid gap-3 sm:gap-4 md:grid-cols-2">
                    <div class="page-card p-3 sm:p-5">
                        <p class="text-xs sm:text-sm font-bold uppercase tracking-wide text-emerald-700">Kompetensi Terbaik</p>
                        @if ($bestWorst['best'])
                            <h3 class="mt-3 text-base sm:text-lg font-bold text-slate-900">{{ $bestWorst['best']['kode'] }}</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 line-clamp-2">{{ $bestWorst['best']['kompetensi'] }}</p>
                            <p class="mt-3 text-xl sm:text-2xl font-bold text-slate-900">{{ $bestWorst['best']['average'] }}</p>
                        @else
                            <p class="mt-3 text-xs sm:text-sm text-slate-500">Belum ada data.</p>
                        @endif
                    </div>

                    <div class="page-card p-3 sm:p-5">
                        <p class="text-xs sm:text-sm font-bold uppercase tracking-wide text-red-700">Kompetensi Terlemah</p>
                        @if ($bestWorst['worst'])
                            <h3 class="mt-3 text-base sm:text-lg font-bold text-slate-900">{{ $bestWorst['worst']['kode'] }}</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-600 line-clamp-2">{{ $bestWorst['worst']['kompetensi'] }}</p>
                            <p class="mt-3 text-xl sm:text-2xl font-bold text-slate-900">{{ $bestWorst['worst']['average'] }}</p>
                        @else
                            <p class="mt-3 text-xs sm:text-sm text-slate-500">Belum ada data.</p>
                        @endif
                    </div>
                </div>

                <div class="grid gap-3 sm:gap-4 md:grid-cols-2">
                    <div class="page-card p-3 sm:p-5">
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Feedback Penilai</h3>
                        <p class="mt-3 rounded-lg bg-slate-50 p-3 sm:p-4 text-xs sm:text-sm text-slate-700">
                            {{ $evaluation->feedback ?? 'Belum ada feedback' }}
                        </p>
                    </div>

                    <div class="page-card p-3 sm:p-5">
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Rekomendasi Pengembangan</h3>
                        <p class="mt-3 rounded-lg bg-slate-50 p-3 sm:p-4 text-xs sm:text-sm text-slate-700 whitespace-pre-line">
                            {{ $evaluation->recommendation ?? 'Belum ada rekomendasi' }}
                        </p>
                    </div>
                </div>

                <div class="page-card p-3 sm:p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900">AI Recommendation Engine</h3>
                            <p class="text-xs text-slate-500 mt-1">Sistem otomatis membaca hasil evaluasi dan menyusun prioritas pengembangan Anda.</p>
                        </div>
                        <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">AUTO</span>
                    </div>

                    @if (!empty($recommendationEngine['items']))
                        <div class="mt-4 space-y-3">
                            @foreach ($recommendationEngine['items'] as $item)
                                <div class="rounded-2xl border border-amber-100 bg-amber-50 p-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">{{ $item['kriteria'] }}</p>
                                            <p class="text-xs text-slate-600 mt-1">{{ $item['insight'] }}</p>
                                        </div>
                                        <div class="text-sm font-bold text-amber-700">
                                            {{ $item['score'] }}
                                        </div>
                                    </div>

                                    <div class="mt-3 text-xs text-slate-600">
                                        @if (!empty($item['benchmark']['school_average']))
                                            Rata-rata sekolah: {{ $item['benchmark']['school_average'] }}
                                        @endif
                                    </div>

                                    <ul class="mt-3 space-y-2 text-sm text-slate-700">
                                        @foreach ($item['recommendations'] as $recommendation)
                                            <li class="flex gap-2">
                                                <span class="mt-1 h-2 w-2 rounded-full bg-cyan-500"></span>
                                                <span>{{ $recommendation }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800">
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

                        <div class="overflow-x-auto">
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

                    <form method="POST" action="{{ route('guru.reflection.store', $evaluation->id) }}"
                        class="space-y-3 sm:space-y-4 p-3 sm:p-5">
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

                        <div class="overflow-x-auto">
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
                                                <span
                                                    class="badge text-xs {{ $gap['gap'] > 0 ? 'badge-danger' : ($gap['gap'] < 0 ? 'badge-success' : 'badge-info') }}">
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
                <div class="page-card p-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-amber-700">
                            <i data-lucide="clock" class="h-6 w-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Hasil penilaian belum difinalisasi</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Status saat ini: <span
                                    class="font-bold uppercase">{{ $pendingEvaluation->status }}</span>.
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
