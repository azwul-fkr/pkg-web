@extends('layouts.app')

@section('title', 'Validasi Evidence')

@section('content')
    <div class="page-stack">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Validasi Evidence</h3>
                    <p class="page-card-subtitle">Tinjau dan validasi evidence yang dikirim guru.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Guru</th>
                            <th>Mapel</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($evidences as $e)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $e->guru->user->name }}</td>
                                <td>{{ $e->subject }}</td>
                                <td>{{ $e->class }}</td>
                                <td>{{ $e->tanggal }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $e->file) }}" target="_blank" class="link-action">Lihat File</a>
                                </td>
                                <td>
                                    <span class="badge {{ $e->status == 'approved' ? 'badge-success' : ($e->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ strtoupper($e->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($e->status == 'pending')
                                        <div class="flex flex-wrap items-center gap-2">
                                            <form action="{{ route('admin.evidences.approve', $e->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-primary">Approve</button>
                                            </form>

                                            <form action="{{ route('admin.evidences.reject', $e->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-danger">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-slate-500">Belum ada evidence.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
