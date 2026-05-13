@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')
    <div class="page-card max-w-2xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Edit Jabatan</h3>
                <p class="page-card-subtitle">Perbarui nama jabatan.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.jabatans.update', $jabatan->id) }}" class="space-y-4 p-5">
            @csrf
            @method('PUT')

            <div>
                <label class="form-label">Nama Jabatan</label>
                <input type="text" name="name" value="{{ $jabatan->name }}" class="form-control" required>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.jabatans.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection
