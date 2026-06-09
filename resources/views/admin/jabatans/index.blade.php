@extends('layouts.app')

@section('title', 'Data Jabatan')

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
                    <h3 class="page-card-title">Data Jabatan</h3>
                    <p class="page-card-subtitle">Kelola jabatan guru yang digunakan pada data master.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openModal('createJabatanModal')">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Jabatan
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jabatans as $jabatan)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $jabatan->name }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" class="btn-secondary" onclick="openEditJabatanModal('{{ $jabatan->id }}', @js($jabatan->name))">Edit</button>
                                        <form action="{{ route('admin.jabatans.destroy', $jabatan->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus jabatan ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-slate-500">Belum ada jabatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $jabatans->links() }}
            </div>
        </div>

        <div id="createJabatanModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Jabatan</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('createJabatanModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.jabatans.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('createJabatanModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editJabatanModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit Jabatan</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('editJabatanModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
                <form method="POST" id="editJabatanForm" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="name" id="edit_jabatan_name" class="form-control" required>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('editJabatanModal')">Batal</button>
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

        function openEditJabatanModal(id, name) {
            document.getElementById('editJabatanForm').action = `/admin/jabatans/${id}`;
            document.getElementById('edit_jabatan_name').value = name;
            openModal('editJabatanModal');
        }
    </script>
@endsection
