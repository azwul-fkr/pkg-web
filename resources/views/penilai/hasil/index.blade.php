@extends('layouts.app')

@section('title', 'Hasil Penilaian Guru')

@section('content')
    <div class="page-stack">
        <div class="page-card p-5">
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="form-label">Periode</label>
                    <select name="period_id" class="form-control">
                        <option value="">-- Semua Periode --</option>
                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}" {{ $selectedPeriod == $period->id ? 'selected' : '' }}>
                                {{ $period->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ route('penilai.hasil.pdf', ['period_id' => request('period_id')]) }}" class="btn-secondary">
                    <i data-lucide="file-down" class="h-4 w-4"></i>
                    Export PDF
                </a>
            </form>
        </div>

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Hasil Penilaian Guru</h3>
                    <p class="page-card-subtitle">Ranking nilai akhir berdasarkan periode yang dipilih.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Guru</th>
                            <th>Periode</th>
                            <th>Nilai Akhir</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $index => $r)
                            <tr>
                                <td><span class="badge badge-info">#{{ $index + 1 }}</span></td>
                                <td class="font-semibold text-slate-900">{{ $r['guru'] }}</td>
                                <td>{{ $r['periode'] }}</td>
                                <td>{{ number_format($r['nilai_akhir'], 2) }}</td>
                                <td>
                                    <a href="{{ route('penilai.hasil.detail', $r['evaluation_id']) }}" class="btn-secondary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">Belum ada hasil penilaian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
