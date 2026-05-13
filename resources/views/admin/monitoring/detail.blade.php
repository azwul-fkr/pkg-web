@extends('layouts.app')

@section('title', 'Review Penilaian Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $evaluation->guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Penilai: {{ $evaluation->user->name }} • Periode: {{ $evaluation->period->name }}
                    </p>
                </div>
                <span class="badge badge-info">{{ strtoupper($evaluation->status) }}</span>
            </div>
        </div>

        @foreach ($groupedScores as $group)
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
        @endforeach

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
                @endif
            </div>
        </form>
    </div>
@endsection
