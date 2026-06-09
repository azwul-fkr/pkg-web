@extends('layouts.app')

@section('title', 'Review Penilaian')

@section('content')
    @php
        $lowestGroup = collect($groupedScores)
            ->map(function ($group) {
                $scores = collect($group['scores']);

                return [
                    'kriteria' => $group['kriteria'],
                    'kompetensi' => $group['kompetensi'],
                    'kode' => $group['kode'],
                    'average' => round($scores->avg('nilai') ?? 0, 2),
                    'low_comments' => $scores
                        ->where('nilai', '<=', 2)
                        ->pluck('comment')
                        ->filter()
                        ->take(2)
                        ->values(),
                ];
            })
            ->sortBy('average')
            ->first();

        $suggestedFeedback = $lowestGroup
            ? 'Guru menunjukkan capaian yang perlu diperkuat pada kompetensi ' . $lowestGroup['kode'] . ' - ' . $lowestGroup['kompetensi'] . ' dengan rata-rata ' . $lowestGroup['average'] . '.'
            : 'Guru telah menyelesaikan proses penilaian. Berikan catatan umpan balik berdasarkan skor dan evidence yang tersedia.';

        $suggestedRecommendation = $lowestGroup
            ? 'Fokus pengembangan disarankan pada kompetensi ' . $lowestGroup['kode'] . ' - ' . $lowestGroup['kompetensi'] . ' melalui pendampingan, refleksi pembelajaran, observasi lanjutan, dan pengumpulan evidence pendukung.'
            : 'Lanjutkan penguatan praktik baik dan dokumentasikan evidence secara konsisten pada periode berikutnya.';

        if ($lowestGroup && $lowestGroup['low_comments']->isNotEmpty()) {
            $suggestedRecommendation .= ' Catatan penilai yang perlu diperhatikan: ' . $lowestGroup['low_comments']->implode('; ') . '.';
        }
    @endphp

    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-cyan-700">Review sebelum submit</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $guru->subject ?? '-' }} | {{ $guru->jabatan->name ?? '-' }} | {{ $evaluation->period->name ?? '-' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Final Score</p>
                        <p class="mt-1 text-2xl font-bold text-cyan-700">{{ $finalScore }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Progress</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ $progressPercentage }}%</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Status</p>
                        <p class="mt-2"><span class="badge badge-info">{{ strtoupper($evaluation->status) }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        @if ($lowestGroup)
            <div class="page-card p-5">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-cyan-50 text-cyan-700">
                        <i data-lucide="sparkles" class="h-6 w-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Rekomendasi Otomatis</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Area prioritas terdeteksi pada
                            <span class="font-bold text-slate-900">{{ $lowestGroup['kode'] }} - {{ $lowestGroup['kompetensi'] }}</span>
                            dengan rata-rata {{ $lowestGroup['average'] }}. Gunakan draft di bawah sebagai bahan, lalu sesuaikan dengan penilaian profesional Anda.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @forelse ($groupedScores as $group)
            <section class="page-card">
                <div class="page-card-header">
                    <div>
                        <h3 class="page-card-title">{{ $group['kriteria'] }}</h3>
                        <p class="page-card-subtitle">{{ $group['kode'] }} - {{ $group['kompetensi'] }}</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Indikator</th>
                                <th>Nilai</th>
                                <th>Komentar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group['scores'] as $score)
                                <tr>
                                    <td>{{ $score['indikator'] }}</td>
                                    <td><span class="badge badge-info">{{ $score['nilai'] }}</span></td>
                                    <td>{{ $score['comment'] ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @empty
            <div class="empty-state">Belum ada skor penilaian.</div>
        @endforelse

        <div class="page-card p-5">
            @if ($evaluation->status == 'draft' || $evaluation->status == 'revised')
                <form method="POST" action="{{ route('penilai.penilaian.final-submit', $evaluation->id) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label">Feedback Penilai</label>
                        <textarea name="feedback" rows="4" class="form-control" required>{{ old('feedback', $evaluation->feedback ?: $suggestedFeedback) }}</textarea>
                    </div>

                    <div>
                        <label class="form-label">Rekomendasi Pengembangan</label>
                        <textarea name="recommendation" rows="4" class="form-control" required>{{ old('recommendation', $evaluation->recommendation ?: $suggestedRecommendation) }}</textarea>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('penilai.penilaian.edit', $evaluation->id) }}" class="btn-secondary">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                            Kembali Edit
                        </a>
                        <button type="submit" class="btn-primary">
                            <i data-lucide="send" class="h-4 w-4"></i>
                            Final Submit
                        </button>
                    </div>
                </form>
            @else
                <div class="space-y-4">
                    <div>
                        <p class="form-label">Feedback Penilai</p>
                        <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                            {{ $evaluation->feedback ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <p class="form-label">Rekomendasi Pengembangan</p>
                        <div class="mt-2 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                            {{ $evaluation->recommendation ?: '-' }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
