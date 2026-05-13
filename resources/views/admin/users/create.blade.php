@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah User</h3>
                <p class="page-card-subtitle">Buat akun baru untuk admin, guru, atau penilai.</p>
            </div>
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
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
