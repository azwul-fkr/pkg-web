@extends('layouts.app')

@section('title', 'Tambah Indikator')

@section('content')
    <div class="page-card max-w-3xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah Indikator</h3>
                <p class="page-card-subtitle">Pilih kriteria dan kompetensi, lalu tulis indikator penilaian.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.indikators.store') }}" class="space-y-4 p-5">
            @csrf

            <div>
                <label class="form-label">Kriteria</label>
                <select id="kriteria-dropdown" class="form-control" required>
                    <option value="">-- Pilih Kriteria --</option>
                    @foreach ($kriterias as $kriteria)
                        <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Kompetensi</label>
                <select name="sub_kriteria_id" id="sub-kriteria-dropdown" class="form-control" required>
                    <option value="">-- Pilih Kompetensi --</option>
                </select>
            </div>

            <div>
                <label class="form-label">Indikator</label>
                <textarea name="name" rows="4" class="form-control" required></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.indikators.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('kriteria-dropdown').addEventListener('change', function() {
            const subDropdown = document.getElementById('sub-kriteria-dropdown');
            subDropdown.innerHTML = '<option value="">-- Pilih Kompetensi --</option>';

            if (!this.value) return;

            fetch('/admin/get-sub-kriterias/' + this.value)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        subDropdown.innerHTML += `<option value="${sub.id}">${sub.kode} - ${sub.name}</option>`;
                    });
                });
        });
    </script>
@endsection
