@extends('layouts.app')

@section('title', 'Data User')

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
                    <h3 class="page-card-title">Data User</h3>
                    <p class="page-card-subtitle">Kelola akun dan role pengguna sistem.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openCreateUserModal()">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah User
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-info">{{ strtoupper($user->role->name) }}</span></td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="btn-secondary"
                                            onclick="openEditUserModal('{{ $user->id }}', @js($user->name), @js($user->email), '{{ $user->role_id }}')"
                                        >
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>
        </div>

        <div id="createUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Tambah User</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('createUserModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('createUserModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-lg rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="page-card-title">Edit User</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('editUserModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" id="editUserForm" class="space-y-4 p-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="edit_user_name" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_user_email" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role_id" id="edit_user_role_id" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('editUserModal')">Batal</button>
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

        function openCreateUserModal() {
            openModal('createUserModal');
        }

        function openEditUserModal(id, name, email, roleId) {
            document.getElementById('editUserForm').action = `/admin/users/${id}`;
            document.getElementById('edit_user_name').value = name;
            document.getElementById('edit_user_email').value = email;
            document.getElementById('edit_user_role_id').value = roleId;
            openModal('editUserModal');
        }
    </script>
@endsection
