@extends('layouts.app')

@section('title', 'Monitoring Penilaian')

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
            </form>
        </div>

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Monitoring Penilaian</h3>
                    <p class="page-card-subtitle">Monitoring hasil penilaian guru.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Penilai</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Nilai Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($monitorings as $m)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-600 font-bold text-white">
                                            {{ strtoupper(substr($m['guru'], 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-900">{{ $m['guru'] }}</span>
                                    </div>
                                </td>
                                <td>{{ $m['subject'] }}</td>
                                <td>{{ $m['penilai'] }}</td>
                                <td>{{ $m['periode'] }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'finalized' => 'badge-success',
                                            'submitted' => 'badge-info',
                                            'draft' => 'badge-warning',
                                            'revised' => 'badge-danger',
                                            'belum_mulai' => 'badge-danger',
                                        ][$m['status']] ?? 'badge-info';

                                        $statusLabel = [
                                            'finalized' => 'Finalized',
                                            'submitted' => 'Menunggu Review Admin',
                                            'draft' => 'Draft Penilai',
                                            'revised' => 'Perlu Revisi',
                                            'belum_mulai' => 'Belum Dinilai',
                                        ][$m['status']] ?? strtoupper($m['status']);
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="font-bold text-cyan-700">
                                    {{ $m['nilai_akhir'] ? number_format($m['nilai_akhir'], 2) : '-' }}
                                </td>
                                <td>
                                    @if ($m['evaluation_id'])
                                        <a href="{{ route('admin.monitoring.detail', $m['evaluation_id']) }}" class="btn-secondary">Review</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-500">Belum ada data monitoring.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
