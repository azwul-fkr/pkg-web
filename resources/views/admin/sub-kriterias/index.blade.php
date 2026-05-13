@extends('layouts.app')

@section('title', 'Data Kompetensi')

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
                    <h3 class="page-card-title">Data Kompetensi / Sub Kriteria</h3>
                    <p class="page-card-subtitle">Kompetensi turunan dari setiap kriteria penilaian.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openModal('createSubModal')">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Kompetensi
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Kode</th>
                            <th>Nama Kompetensi</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subKriterias as $sub)
                            <tr>
                                <td>{{ $sub->kriteria->name }}</td>
                                <td><span class="badge badge-info">{{ $sub->kode }}</span></td>
                                <td class="font-semibold text-slate-900">{{ $sub->name }}</td>
                                <td>{{ $sub->bobot }}%</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="btn-secondary"
                                            onclick="openEditSubModal('{{ $sub->id }}', '{{ $sub->kriteria_id }}', @js($sub->kode), @js($sub->name), '{{ $sub->bobot }}')"
                                        >
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.sub-kriterias.destroy', $sub->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus kompetensi ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-slate-500">Belum ada kompetensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $subKriterias->links() }}
            </div>
        </div>

        <div id="createSubModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Kompetensi</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('createSubModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.sub-kriterias.store') }}" class="space-y-4 p-5">
                    @csrf
                    @include('admin.sub-kriterias._form-fields', ['prefix' => 'create', 'subKriteria' => null])
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('createSubModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editSubModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit Kompetensi</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('editSubModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" id="editSubForm" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')
                    @include('admin.sub-kriterias._form-fields', ['prefix' => 'edit', 'subKriteria' => null])
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('editSubModal')">Batal</button>
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

        function openEditSubModal(id, kriteriaId, kode, name, bobot) {
            document.getElementById('editSubForm').action = `/admin/sub-kriterias/${id}`;
            document.getElementById('edit_kriteria_id').value = kriteriaId;
            document.getElementById('edit_kode').value = kode;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_bobot').value = bobot;
            openModal('editSubModal');
        }
    </script>
@endsection
