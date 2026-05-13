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
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $guru->user?->name ?? auth()->user()->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Mata Pelajaran: {{ $guru->subject ?? '-' }} | Periode Aktif: {{ $period?->name ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-slate-500">Total Evidence</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalEvidence }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-emerald-600">Approved</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $approvedEvidence }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-amber-600">Pending</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingEvidence }}</p>
            </div>
            <div class="page-card p-5">
                <p class="text-sm font-semibold text-red-600">Rejected</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $rejectedEvidence }}</p>
            </div>
        </div>

        @if ($evaluation)
            <div class="page-card p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Hasil Penilaian Terakhir</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $finalScore }}</p>
                    </div>
                    <span class="badge badge-info">{{ strtoupper($evaluation->status) }}</span>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="page-card p-5">
                    <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Kompetensi Terbaik</p>
                    @if ($bestWorst['best'])
                        <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $bestWorst['best']['kode'] }}</h3>
                        <p class="mt-1 text-slate-600">{{ $bestWorst['best']['kompetensi'] }}</p>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ $bestWorst['best']['average'] }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                    @endif
                </div>

                <div class="page-card p-5">
                    <p class="text-sm font-bold uppercase tracking-wide text-red-700">Kompetensi Terlemah</p>
                    @if ($bestWorst['worst'])
                        <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $bestWorst['worst']['kode'] }}</h3>
                        <p class="mt-1 text-slate-600">{{ $bestWorst['worst']['kompetensi'] }}</p>
                        <p class="mt-3 text-2xl font-bold text-slate-900">{{ $bestWorst['worst']['average'] }}</p>
                    @else
                        <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="page-card p-5">
                    <h3 class="font-bold text-slate-900">Feedback Penilai</h3>
                    <p class="mt-3 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                        {{ $evaluation->feedback ?? 'Belum ada feedback' }}
                    </p>
                </div>

                <div class="page-card p-5">
                    <h3 class="font-bold text-slate-900">Rekomendasi Pengembangan</h3>
                    <p class="mt-3 rounded-lg bg-slate-50 p-4 text-sm text-slate-700">
                        {{ $evaluation->recommendation ?? 'Belum ada rekomendasi' }}
                    </p>
                </div>
            </div>

            <div class="page-card">
                <div class="page-card-header">
                    <div>
                        <h3 class="page-card-title">Refleksi Diri Guru</h3>
                        <p class="page-card-subtitle">Catat refleksi dan rencana perbaikan setelah menerima hasil.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('guru.reflection.store', $evaluation->id) }}" class="space-y-4 p-5">
                    @csrf

                    <div>
                        <label class="form-label">Refleksi Diri</label>
                        <textarea name="reflection" rows="4" class="form-control"></textarea>
                    </div>

                    <div>
                        <label class="form-label">Rencana Perbaikan</label>
                        <textarea name="improvement_plan" rows="4" class="form-control"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">Simpan Refleksi</button>
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
                        <table class="app-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kompetensi</th>
                                    <th>Self Assessment</th>
                                    <th>Penilai</th>
                                    <th>Gap</th>
                                    <th>Interpretasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gapAnalysis as $gap)
                                    <tr>
                                        <td>{{ $gap['kode'] }}</td>
                                        <td>{{ $gap['kompetensi'] }}</td>
                                        <td>{{ $gap['self_avg'] }}</td>
                                        <td>{{ $gap['penilai_avg'] }}</td>
                                        <td>
                                            <span class="badge {{ $gap['gap'] > 0 ? 'badge-danger' : ($gap['gap'] < 0 ? 'badge-success' : 'badge-info') }}">
                                                {{ $gap['gap'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($gap['gap'] >= 1)
                                                Guru cenderung overestimate
                                            @elseif($gap['gap'] <= -1)
                                                Guru cenderung underestimate
                                            @else
                                                Penilaian relatif objektif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">Belum ada hasil penilaian finalized.</div>
        @endif
        @endif
    </div>
@endsection
