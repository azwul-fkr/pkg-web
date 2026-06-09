@extends('layouts.app')

@section('title', 'Assignment Penilai')

@section('content')
    <div class="page-stack">
        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Assignment Penilai</h3>
                    <p class="page-card-subtitle">Kelola penugasan penilai untuk setiap guru dan periode.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openModal()">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Assignment
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Penilai</th>
                            <th>Guru</th>
                            <th>Periode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $assignment)
                            <tr>
                                <td>{{ $assignment->penilai->name }}</td>
                                <td>{{ $assignment->guru->user->name }}</td>
                                <td>{{ $assignment->period->name }}</td>
                                <td>
                                    <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('Hapus assignment ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada assignment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Assignment</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.assignments.store') }}" class="space-y-4 p-5">
                    @csrf

                    <div>
                        <label class="form-label">Penilai</label>
                        <select name="penilai_id" class="form-control">
                            @foreach ($penilais as $penilai)
                                <option value="{{ $penilai->id }}">{{ $penilai->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Guru</label>
                        <select name="guru_id" class="form-control">
                            @foreach ($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Periode</label>
                        <select name="period_id" class="form-control">
                            @foreach ($periods as $period)
                                <option value="{{ $period->id }}">{{ $period->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('modal').classList.remove('flex');
        }
    </script>
@endsection
