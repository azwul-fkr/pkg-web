@extends('layouts.app')

@section('title', 'Tambah Kompetensi')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah Kompetensi</h3>
                <p class="page-card-subtitle">Tambahkan sub kriteria dan bobotnya.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.sub-kriterias.store') }}" class="space-y-4 p-5">
            @csrf

            <div>
                <label class="form-label">Kriteria</label>
                <select name="kriteria_id" class="form-control" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach ($kriterias as $kriteria)
                        <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Kode Kompetensi</label>
                <input type="text" name="kode" class="form-control" placeholder="Contoh: K1.1" required>
            </div>

            <div>
                <label class="form-label">Nama Kompetensi</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Bobot</label>
                <input type="number" step="0.01" name="bobot" class="form-control" required>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.sub-kriterias.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
