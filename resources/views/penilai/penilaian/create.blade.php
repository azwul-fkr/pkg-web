@extends('layouts.app')

@section('title', 'Form Penilaian Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $guru->subject }} • {{ $guru->jabatan->name }}</p>
                </div>
                <span class="badge badge-info">{{ $completedIndikator }} / {{ $totalIndikator }} indikator</span>
            </div>

            <div class="mt-5">
                <div class="mb-2 flex items-center justify-between text-sm font-semibold text-slate-600">
                    <span>Progress Penilaian</span>
                    <span>{{ $progressPercentage }}%</span>
                </div>
                <div class="h-3 rounded-full bg-slate-100">
                    <div class="h-3 rounded-full bg-cyan-600" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Evidence Guru</h3>
                    <p class="page-card-subtitle">Bukti pendukung yang dapat digunakan saat menilai.</p>
                </div>
            </div>

            <div class="grid gap-4 p-5 md:grid-cols-2">
                @forelse ($evidences as $evidence)
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <div class="grid gap-2 text-sm text-slate-600">
                            <p><span class="font-bold text-slate-800">Mapel:</span> {{ $evidence->subject }}</p>
                            <p><span class="font-bold text-slate-800">Kelas:</span> {{ $evidence->class }}</p>
                            <p><span class="font-bold text-slate-800">Tanggal:</span> {{ $evidence->tanggal }}</p>
                            <p>{{ $evidence->description }}</p>
                        </div>
                        @if (!empty($evidence->file))
                            <a href="{{ asset('storage/' . $evidence->file) }}" target="_blank" class="mt-3 inline-flex link-action">Lihat File</a>
                        @endif
                    </div>
                @empty
                    <div class="empty-state md:col-span-2">Belum ada evidence.</div>
                @endforelse
            </div>
        </div>

        <form method="POST" action="{{ route('penilai.penilaian.store', $guru->id) }}" class="page-stack">
            @csrf

            @foreach ($kriterias as $kriteria)
                <section class="page-card">
                    <div class="page-card-header">
                        <div>
                            <h3 class="page-card-title">{{ $kriteria->name }}</h3>
                            <p class="page-card-subtitle">Bobot: {{ $kriteria->bobot }}%</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        @foreach ($kriteria->subKriterias as $sub)
                            <div class="rounded-lg border border-slate-200 p-4">
                                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                    <h4 class="font-bold text-slate-900">{{ $sub->kode }} - {{ $sub->name }}</h4>
                                    <span class="badge badge-info">Bobot {{ $sub->bobot }}%</span>
                                </div>

                                <div class="space-y-4">
                                    @foreach ($sub->indikators as $indikator)
                                        <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4">
                                            <p class="font-semibold text-slate-900">{{ $indikator->name }}</p>

                                            <div class="mt-4 space-y-2">
                                                @foreach ($indikator->indikatorScores as $rubrik)
                                                    <label class="flex gap-3 rounded-lg border border-slate-200 bg-white p-3 text-sm text-slate-700">
                                                        <input type="radio" name="scores[{{ $indikator->id }}]" value="{{ $rubrik->score }}" class="mt-1 border-slate-300 text-cyan-600 focus:ring-cyan-500" required>
                                                        <span><strong>Score {{ $rubrik->score }}</strong>: {{ $rubrik->description }}</span>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="mt-4">
                                                <label class="form-label">Komentar Penilai</label>
                                                <textarea name="comments[{{ $indikator->id }}]" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="flex flex-wrap justify-end gap-2">
                @if (isset($evaluation))
                    <a href="{{ route('penilai.penilaian.review', $evaluation->id) }}" class="btn-secondary">Review Penilaian</a>
                @endif
                <button type="submit" name="action" value="draft" class="btn-primary">Simpan Draft</button>
            </div>
        </form>
    </div>
@endsection
