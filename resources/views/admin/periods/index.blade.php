@extends('layouts.app')

@section('title', 'Data Periode')

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
                    <h3 class="page-card-title">Data Periode</h3>
                    <p class="page-card-subtitle">Atur periode aktif dan status kunci penilaian.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openCreateModal()">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Periode
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Lock</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($periods as $period)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $period->name }}</td>
                                <td>{{ $period->start_date }}</td>
                                <td>{{ $period->end_date }}</td>
                                <td>
                                    <span class="badge {{ $period->is_active ? 'badge-success' : 'badge-warning' }}">
                                        {{ $period->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $period->is_locked ? 'badge-danger' : 'badge-success' }}">
                                        {{ $period->is_locked ? 'Dikunci' : 'Dibuka' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="btn-secondary"
                                            onclick="openEditModal(
                                                '{{ $period->id }}',
                                                @js($period->name),
                                                '{{ $period->start_date }}',
                                                '{{ $period->end_date }}',
                                                '{{ (int) $period->is_active }}',
                                                '{{ (int) $period->is_locked }}'
                                            )"
                                        >
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.periods.destroy', $period->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus periode ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada periode.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="createModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Periode</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeCreateModal()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.periods.store') }}" class="space-y-4 p-5">
                    @csrf

                    <div>
                        <label class="form-label">Nama Periode</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_active" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        Jadikan periode aktif
                    </label>

                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_locked" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        Kunci penilaian
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeCreateModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit Periode</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeEditModal()">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" id="editForm" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="form-label">Nama Periode</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_active" id="edit_is_active" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        Jadikan aktif
                    </label>

                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" name="is_locked" id="edit_is_locked" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        Kunci penilaian
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function hideModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openCreateModal() {
            showModal('createModal');
        }

        function closeCreateModal() {
            hideModal('createModal');
        }

        function openEditModal(id, name, startDate, endDate, isActive, isLocked) {
            showModal('editModal');
            document.getElementById('editForm').action = '/admin/periods/' + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_start_date').value = startDate;
            document.getElementById('edit_end_date').value = endDate;
            document.getElementById('edit_is_active').checked = isActive == 1;
            document.getElementById('edit_is_locked').checked = isLocked == 1;
        }

        function closeEditModal() {
            hideModal('editModal');
        }
    </script>
@endsection
