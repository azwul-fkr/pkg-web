@extends('layouts.app')

@section('title', 'Guru Yang Harus Dinilai')

@section('content')
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Guru Yang Harus Dinilai</h3>
                <p class="page-card-subtitle">Daftar assignment penilaian yang menjadi tanggung jawab Anda.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $assignment->guru->user->name }}</td>
                            <td>{{ $assignment->guru->subject }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'belum_mulai' => 'badge-danger',
                                        'draft' => 'badge-warning',
                                        'submitted' => 'badge-info',
                                        'reviewed' => 'badge-success',
                                        'revised' => 'badge-danger',
                                        'finalized' => 'badge-success',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$assignment->evaluation_status] ?? 'badge-info' }}">
                                    {{ str_replace('_', ' ', strtoupper($assignment->evaluation_status)) }}
                                </span>
                            </td>
                            <td>
                                @if ($assignment->evaluation_status == 'belum_mulai')
                                    <a href="{{ route('penilai.penilaian.create', $assignment->guru->id) }}" class="btn-primary">Mulai Penilaian</a>
                                @elseif($assignment->evaluation_status == 'draft')
                                    <a href="{{ route('penilai.penilaian.edit', $assignment->evaluation_id) }}" class="btn-secondary">Lanjutkan Draft</a>
                                @elseif($assignment->evaluation_status == 'revised')
                                    <a href="{{ route('penilai.penilaian.edit', $assignment->evaluation_id) }}" class="btn-secondary">Revisi Penilaian</a>
                                @else
                                    <a href="{{ route('penilai.hasil.detail', $assignment->evaluation_id) }}" class="btn-secondary">Lihat Hasil</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-slate-500">Belum ada guru yang ditugaskan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
