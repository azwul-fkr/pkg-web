@extends('layouts.app')

@section('title', 'Data Rubrik Score')

@section('content')
    <div class="page-stack">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Data Rubrik Score</h3>
                    <p class="page-card-subtitle">Rubrik skor untuk setiap indikator penilaian.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openCreateScoreModal()">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Rubrik
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Kompetensi</th>
                            <th>Indikator</th>
                            <th>Score</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indikatorScores as $item)
                            <tr>
                                <td>{{ $item->indikator->subKriteria->kriteria->name }}</td>
                                <td><span class="badge badge-info">{{ $item->indikator->subKriteria->kode }}</span></td>
                                <td>{{ $item->indikator->name }}</td>
                                <td><span class="badge badge-success">{{ $item->score }}</span></td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="btn-secondary"
                                            onclick="openEditScoreModal(
                                                '{{ $item->id }}',
                                                '{{ $item->indikator->subKriteria->kriteria_id }}',
                                                '{{ $item->indikator->sub_kriteria_id }}',
                                                '{{ $item->indikator_id }}',
                                                '{{ $item->score }}',
                                                @js($item->description)
                                            )"
                                        >
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.indikator-scores.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus rubrik ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-slate-500">Belum ada rubrik score.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $indikatorScores->links() }}
            </div>
        </div>

        <div id="scoreModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-2xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 id="scoreModalTitle" class="page-card-title">Tambah Rubrik Score</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('scoreModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" id="scoreForm" class="space-y-4 p-5">
                    @csrf
                    <input type="hidden" name="_method" id="score_method" value="POST">

                    <div>
                        <label class="form-label">Kriteria</label>
                        <select id="score_kriteria_id" class="form-control" required>
                            <option value="">-- Pilih Kriteria --</option>
                            @foreach ($kriterias as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Kompetensi</label>
                        <select id="score_sub_id" class="form-control" required>
                            <option value="">-- Pilih Kompetensi --</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Indikator</label>
                        <select name="indikator_id" id="score_indikator_id" class="form-control" required>
                            <option value="">-- Pilih Indikator --</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Score</label>
                        <select name="score" id="score_value" class="form-control" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Deskripsi Rubrik</label>
                        <textarea name="description" id="score_description" rows="4" class="form-control" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('scoreModal')">Batal</button>
                        <button type="submit" class="btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function loadScoreSubs(kriteriaId, selectedSubId = '') {
            const subDropdown = document.getElementById('score_sub_id');
            const indikatorDropdown = document.getElementById('score_indikator_id');
            subDropdown.innerHTML = '<option value="">-- Pilih Kompetensi --</option>';
            indikatorDropdown.innerHTML = '<option value="">-- Pilih Indikator --</option>';
            if (!kriteriaId) return Promise.resolve();

            return fetch('/admin/get-sub-kriterias/' + kriteriaId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        const selected = sub.id == selectedSubId ? 'selected' : '';
                        subDropdown.innerHTML += `<option value="${sub.id}" ${selected}>${sub.kode} - ${sub.name}</option>`;
                    });
                });
        }

        function loadScoreIndikators(subId, selectedIndikatorId = '') {
            const indikatorDropdown = document.getElementById('score_indikator_id');
            indikatorDropdown.innerHTML = '<option value="">-- Pilih Indikator --</option>';
            if (!subId) return Promise.resolve();

            return fetch('/admin/get-indikators/' + subId)
                .then(response => response.json())
                .then(data => {
                    data.forEach(indikator => {
                        const selected = indikator.id == selectedIndikatorId ? 'selected' : '';
                        indikatorDropdown.innerHTML += `<option value="${indikator.id}" ${selected}>${indikator.name}</option>`;
                    });
                });
        }

        function openCreateScoreModal() {
            document.getElementById('scoreModalTitle').textContent = 'Tambah Rubrik Score';
            document.getElementById('scoreForm').action = '{{ route('admin.indikator-scores.store') }}';
            document.getElementById('score_method').value = 'POST';
            document.getElementById('score_kriteria_id').value = '';
            document.getElementById('score_value').value = '1';
            document.getElementById('score_description').value = '';
            loadScoreSubs('');
            openModal('scoreModal');
        }

        function openEditScoreModal(id, kriteriaId, subId, indikatorId, score, description) {
            document.getElementById('scoreModalTitle').textContent = 'Edit Rubrik Score';
            document.getElementById('scoreForm').action = `/admin/indikator-scores/${id}`;
            document.getElementById('score_method').value = 'PUT';
            document.getElementById('score_kriteria_id').value = kriteriaId;
            document.getElementById('score_value').value = score;
            document.getElementById('score_description').value = description;
            loadScoreSubs(kriteriaId, subId)
                .then(() => loadScoreIndikators(subId, indikatorId))
                .then(() => openModal('scoreModal'));
        }

        document.getElementById('score_kriteria_id').addEventListener('change', function() {
            loadScoreSubs(this.value);
        });

        document.getElementById('score_sub_id').addEventListener('change', function() {
            loadScoreIndikators(this.value);
        });
    </script>
@endsection
