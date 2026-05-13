@extends('layouts.app')

@section('title', 'Edit Kompetensi')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Edit Kompetensi</h3>
                <p class="page-card-subtitle">Perbarui data kompetensi.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.sub-kriterias.update', $subKriteria->id) }}" class="space-y-4 p-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Kriteria</label>
                <select name="kriteria_id" class="form-control" required>
                    @foreach ($kriterias as $kriteria)
                        <option value="{{ $kriteria->id }}" {{ $subKriteria->kriteria_id == $kriteria->id ? 'selected' : '' }}>
                            {{ $kriteria->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Kode Kompetensi</label>
                <input type="text" name="kode" value="{{ $subKriteria->kode }}" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Nama Kompetensi</label>
                <input type="text" name="name" value="{{ $subKriteria->name }}" class="form-control" required>
            </div>

            <div>
                <label class="form-label">Bobot</label>
                <input type="number" step="0.01" name="bobot" value="{{ $subKriteria->bobot }}" class="form-control" required>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.sub-kriterias.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
