@extends('layouts.app')

@section('title', 'Tambah Rubrik Score')

@section('content')
    <div class="page-card max-w-3xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Tambah Rubrik Score</h3>
                <p class="page-card-subtitle">Tentukan skor dan deskripsi rubrik untuk indikator.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.indikator-scores.store') }}" class="space-y-4 p-5">
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
                <select id="sub-dropdown" class="form-control" required>
                    <option value="">-- Pilih Kompetensi --</option>
                </select>
            </div>

            <div>
                <label class="form-label">Indikator</label>
                <select name="indikator_id" id="indikator-dropdown" class="form-control" required>
                    <option value="">-- Pilih Indikator --</option>
                </select>
            </div>

            <div>
                <label class="form-label">Score</label>
                <select name="score" class="form-control" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>

            <div>
                <label class="form-label">Deskripsi Rubrik</label>
                <textarea name="description" rows="5" class="form-control" required></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('admin.indikator-scores.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('kriteria-dropdown').addEventListener('change', function() {
            const subDropdown = document.getElementById('sub-dropdown');
            const indikatorDropdown = document.getElementById('indikator-dropdown');
            subDropdown.innerHTML = '<option value="">-- Pilih Kompetensi --</option>';
            indikatorDropdown.innerHTML = '<option value="">-- Pilih Indikator --</option>';

            if (!this.value) return;

            fetch('/admin/get-sub-kriterias/' + this.value)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        subDropdown.innerHTML += `<option value="${sub.id}">${sub.kode} - ${sub.name}</option>`;
                    });
                });
        });

        document.getElementById('sub-dropdown').addEventListener('change', function() {
            const indikatorDropdown = document.getElementById('indikator-dropdown');
            indikatorDropdown.innerHTML = '<option value="">-- Pilih Indikator --</option>';

            if (!this.value) return;

            fetch('/admin/get-indikators/' + this.value)
                .then(response => response.json())
                .then(data => {
                    data.forEach(indikator => {
                        indikatorDropdown.innerHTML += `<option value="${indikator.id}">${indikator.name}</option>`;
                    });
                });
        });
    </script>
@endsection
