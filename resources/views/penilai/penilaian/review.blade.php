@extends('layouts.app')

@section('title', 'Review Penilaian')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $evaluation->guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Periode: {{ $evaluation->period->name }}</p>
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
                    <div class="flex flex-wrap gap-2">
                        <span class="badge badge-info">Bobot Kompetensi {{ $group['bobot_sub'] }}%</span>
                        <span class="badge badge-success">Bobot Kriteria {{ $group['bobot_kriteria'] }}%</span>
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

        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Final Score</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $finalScore }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('penilai.penilaian.edit', $evaluation->id) }}" class="btn-secondary">Kembali Edit</a>
                    @if ($evaluation->status == 'draft' || $evaluation->status == 'revised')
                        <form method="POST" action="{{ route('penilai.penilaian.final-submit', $evaluation->id) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Submit Final Penilaian</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
