@extends('layouts.app')

@section('title', 'Detail Penilaian Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $evaluation->guru->user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">Periode: {{ $evaluation->period->name }}</p>
                </div>
                <div class="rounded-lg bg-cyan-50 px-4 py-3 text-center">
                    <p class="text-xs font-bold uppercase tracking-wide text-cyan-700">Final Score</p>
                    <p class="text-2xl font-bold text-cyan-900">{{ $finalScore }}</p>
                </div>
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
                            @foreach ($group['indikators'] as $indikator)
                                <tr>
                                    <td>{{ $indikator['indikator'] }}</td>
                                    <td><span class="badge badge-info">{{ $indikator['nilai'] }}</span></td>
                                    <td>{{ $indikator['comment'] ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Analytics Penilaian</h3>
                    <p class="page-card-subtitle">Rata-rata dan weighted score per kompetensi.</p>
                </div>
            </div>

            <div class="space-y-5 p-5">
                @foreach ($analytics as $item)
                    <div class="rounded-lg border border-slate-200">
                        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                            <h4 class="font-bold text-slate-900">{{ $item['kriteria'] }}</h4>
                            <span class="badge badge-info">Rata-rata {{ $item['average'] }}</span>
                        </div>
                        <div class="table-wrap">
                            <table class="app-table">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kompetensi</th>
                                        <th>Average</th>
                                        <th>Weighted Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['subs'] as $sub)
                                        <tr>
                                            <td>{{ $sub['kode'] }}</td>
                                            <td>{{ $sub['kompetensi'] }}</td>
                                            <td>{{ $sub['average'] }}</td>
                                            <td>{{ $sub['weighted_score'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="page-card p-5">
                <p class="text-sm font-bold uppercase tracking-wide text-emerald-700">Kompetensi Terbaik</p>
                @if ($bestWorst['best'])
                    <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $bestWorst['best']['kode'] }} - {{ $bestWorst['best']['kompetensi'] }}</h3>
                    <p class="mt-2 text-sm text-slate-500">Average: {{ $bestWorst['best']['average'] }}</p>
                @else
                    <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                @endif
            </div>

            <div class="page-card p-5">
                <p class="text-sm font-bold uppercase tracking-wide text-red-700">Kompetensi Terlemah</p>
                @if ($bestWorst['worst'])
                    <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $bestWorst['worst']['kode'] }} - {{ $bestWorst['worst']['kompetensi'] }}</h3>
                    <p class="mt-2 text-sm text-slate-500">Average: {{ $bestWorst['worst']['average'] }}</p>
                @else
                    <p class="mt-3 text-sm text-slate-500">Belum ada data.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
