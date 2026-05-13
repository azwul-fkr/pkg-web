@extends('layouts.app')

@section('title', 'Tambah Jabatan')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah Jabatan</h3>
                <p class="page-card-subtitle">Masukkan nama jabatan baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.jabatans.store') }}" class="space-y-4 p-5">
            @csrf

            <div>
                <label class="form-label">Nama Jabatan</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.jabatans.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
