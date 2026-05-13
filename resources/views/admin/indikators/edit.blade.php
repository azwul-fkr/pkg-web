@extends('layouts.app')

@section('title', 'Edit Indikator')

@section('content')
    <div class="page-card max-w-3xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Edit Indikator</h3>
                <p class="page-card-subtitle">Perbarui indikator penilaian.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.indikators.update', $indikator->id) }}" class="space-y-4 p-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Kriteria</label>
                <input type="text" value="{{ $indikator->subKriteria->kriteria->name }}" class="form-control bg-slate-50" disabled>
            </div>

            <div>
                <label class="form-label">Kompetensi</label>
                <select name="sub_kriteria_id" class="form-control" required>
                    <option value="{{ $indikator->sub_kriteria_id }}" selected>
                        {{ $indikator->subKriteria->kode }} - {{ $indikator->subKriteria->name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="form-label">Indikator</label>
                <textarea name="name" rows="4" class="form-control" required>{{ $indikator->name }}</textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.indikators.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
