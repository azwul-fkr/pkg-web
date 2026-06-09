@extends('layouts.app')

@section('title', 'Data Guru')

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
                    <h3 class="page-card-title">Data Guru</h3>
                    <p class="page-card-subtitle">Daftar profil guru yang akan dinilai.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openModal('createGuruModal')">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Guru
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Mapel</th>
                            <th>Jabatan</th>
                            <th>No HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $guru)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $guru->user->name }}</td>
                                <td>{{ $guru->nip }}</td>
                                <td>{{ $guru->subject }}</td>
                                <td>{{ $guru->jabatan->name ?? ($guru->jabatan->nama ?? '-') }}</td>
                                <td>{{ $guru->phone }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" class="btn-secondary"
                                            onclick="openEditGuruModal(
                                                '{{ $guru->id }}',
                                                '{{ $guru->user_id }}',
                                                @js($guru->user->name),
                                                '{{ $guru->jabatan_id }}',
                                                @js($guru->nip),
                                                @js($guru->subject),
                                                @js($guru->phone),
                                                @js($guru->address)
                                            )">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.gurus.destroy', $guru->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger"
                                                onclick="return confirm('Hapus guru ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $gurus->links() }}
            </div>
        </div>

        <div id="createGuruModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-3xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah Guru</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100"
                        onclick="closeModal('createGuruModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.gurus.store') }}" class="grid gap-4 p-5 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label class="form-label">User Guru</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Pilih User Guru --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan_id" class="form-control" required>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->name ?? $jabatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">No HP</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 sm:col-span-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('createGuruModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editGuruModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-3xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit Guru</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100"
                        onclick="closeModal('editGuruModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="" id="editGuruForm" class="grid gap-4 p-5 sm:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">User Guru</label>
                        <select name="user_id" id="edit_guru_user_id" class="form-control" required></select>
                    </div>
                    <div>
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan_id" id="edit_guru_jabatan_id" class="form-control" required>
                            @foreach ($jabatans as $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->name ?? $jabatan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" id="edit_guru_nip" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" name="subject" id="edit_guru_subject" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">No HP</label>
                        <input type="text" name="phone" id="edit_guru_phone" class="form-control">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" id="edit_guru_address" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 sm:col-span-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('editGuruModal')">Batal</button>
                        <button type="submit" class="btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const availableGuruUsers = @json($users->map(fn($user) => ['id' => $user->id, 'name' => $user->name])->values());

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function openEditGuruModal(id, userId, userName, jabatanId, nip, subject, phone, address) {
            const userSelect = document.getElementById('edit_guru_user_id');
            userSelect.innerHTML = `<option value="${userId}">${userName}</option>`;

            availableGuruUsers.forEach(user => {
                if (user.id != userId) {
                    userSelect.innerHTML += `<option value="${user.id}">${user.name}</option>`;
                }
            });

            document.getElementById('editGuruForm').action =
                `{{ url('admin/gurus') }}/${id}`;
            document.getElementById('edit_guru_jabatan_id').value = jabatanId;
            document.getElementById('edit_guru_nip').value = nip ?? '';
            document.getElementById('edit_guru_subject').value = subject ?? '';
            document.getElementById('edit_guru_phone').value = phone ?? '';
            document.getElementById('edit_guru_address').value = address ?? '';
            openModal('editGuruModal');
        }
    </script>
@endsection
