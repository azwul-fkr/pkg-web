@extends('layouts.app')

@section('title', 'Review Penilaian Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $evaluation->guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Penilai: {{ $evaluation->penilai?->name ?? '-' }} • Periode: {{ $evaluation->period->name }}
                    </p>
                </div>
                <span class="badge badge-info">{{ strtoupper($evaluation->status) }}</span>
            </div>
        </div>

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
            <div class="empty-state">Belum ada skor penilaian untuk evaluation ini.</div>
        @endforelse

        <form method="POST" action="{{ route('admin.monitoring.review', $evaluation->id) }}" class="page-card">
            @csrf

            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Keputusan Review</h3>
                    <p class="page-card-subtitle">Final score: <span class="font-bold text-cyan-700">{{ $finalScore }}</span></p>
                </div>
            </div>

            <div class="space-y-4 p-5">
                <div>
                    <label class="form-label">Feedback Penilai</label>
                    <textarea name="feedback" rows="4" class="form-control">{{ $evaluation->feedback }}</textarea>
                </div>

                <div>
                    <label class="form-label">Rekomendasi Pengembangan</label>
                    <textarea name="recommendation" rows="4" class="form-control">{{ $evaluation->recommendation }}</textarea>
                </div>

                @if ($evaluation->status == 'submitted')
                    <div>
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="revision_note" rows="4" class="form-control"></textarea>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-2">
                        <button type="submit" name="action" value="revisi" class="btn-danger">Minta Revisi</button>
                        <button type="submit" name="action" value="approve" class="btn-primary">Approve</button>
                    </div>
                @elseif ($evaluation->status == 'finalized')
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        Penilaian sudah difinalisasi dan hasilnya dapat dilihat oleh guru.
                    </div>
                @elseif ($evaluation->status == 'draft')
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                        Penilai masih menyimpan draft. Admin dapat melakukan review setelah penilai submit.
                    </div>
                @elseif ($evaluation->status == 'revised')
                    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        Penilaian sedang menunggu revisi dari penilai.
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
