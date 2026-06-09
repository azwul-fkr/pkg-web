@extends('layouts.app')

@section('title', 'Data Kriteria')

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
                    <h3 class="page-card-title">Data Kriteria</h3>
                    <p class="page-card-subtitle">Total bobot saat ini: <span class="font-bold text-cyan-700">{{ $totalBobot }}%</span></p>
                </div>

                <button type="button" class="btn-primary" onclick="openModal('createKriteriaModal')">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Kriteria
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kriterias as $kriteria)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $kriteria->name }}</td>
                                <td>{{ $kriteria->bobot }}%</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" class="btn-secondary" onclick="openEditKriteriaModal('{{ $kriteria->id }}', @js($kriteria->name), '{{ $kriteria->bobot }}')">Edit</button>
                                        <form action="{{ route('admin.kriterias.destroy', $kriteria->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus kriteria ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-slate-500">Belum ada kriteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $kriterias->links() }}
            </div>
        </div>

        <div id="createKriteriaModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Kriteria</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('createKriteriaModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.kriterias.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Bobot</label>
                        <input type="number" step="0.01" name="bobot" class="form-control" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('createKriteriaModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editKriteriaModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit Kriteria</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('editKriteriaModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" id="editKriteriaForm" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">Nama Kriteria</label>
                        <input type="text" name="name" id="edit_kriteria_name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Bobot</label>
                        <input type="number" step="0.01" name="bobot" id="edit_kriteria_bobot" class="form-control" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('editKriteriaModal')">Batal</button>
                        <button type="submit" class="btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openEditKriteriaModal(id, name, bobot) {
            document.getElementById('editKriteriaForm').action = `/admin/kriterias/${id}`;
            document.getElementById('edit_kriteria_name').value = name;
            document.getElementById('edit_kriteria_bobot').value = bobot;
            openModal('editKriteriaModal');
        }
    </script>
@endsection
