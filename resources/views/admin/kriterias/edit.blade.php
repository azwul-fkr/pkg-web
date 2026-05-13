@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Edit Kriteria</h3>
                <p class="page-card-subtitle">Perbarui data kriteria penilaian.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.kriterias.update', $kriteria->id) }}" class="space-y-4 p-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Nama Kriteria</label>
                <input type="text" name="name" value="{{ $kriteria->name }}" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Bobot</label>
                <input type="number" step="0.01" name="bobot" value="{{ $kriteria->bobot }}" class="form-control" required>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.kriterias.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
