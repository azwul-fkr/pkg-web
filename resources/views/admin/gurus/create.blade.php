@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
    <div class="page-card max-w-3xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah Guru</h3>
                <p class="page-card-subtitle">Lengkapi profil guru untuk kebutuhan penilaian.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.gurus.store') }}" class="grid gap-4 p-5 sm:grid-cols-2">
            @csrf

            <div>
                <label class="form-label">User Guru</label>
                <select name="user_id" class="form-control" required>
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
                <input type="text" name="nip" class="form-control">
            </div>

            <div>
                <label class="form-label">Mata Pelajaran</label>
                <input type="text" name="subject" class="form-control">
            </div>

            <div>
                <label class="form-label">No HP</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="sm:col-span-2">
                <label class="form-label">Alamat</label>
                <textarea name="address" rows="4" class="form-control"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 sm:col-span-2">
                <a href="{{ route('admin.gurus.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
