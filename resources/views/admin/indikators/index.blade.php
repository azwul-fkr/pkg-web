@extends('layouts.app')

@section('title', 'Data Indikator')

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
                    <h3 class="page-card-title">Data Indikator</h3>
                    <p class="page-card-subtitle">Daftar indikator penilaian berdasarkan kriteria dan kompetensi.</p>
                </div>

                <button type="button" class="btn-primary" onclick="openCreateIndikatorModal()">
                    <i data-lucide="plus" class="h-4 w-4"></i>
                    Tambah Indikator
                </button>
            </div>

            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th>Kompetensi</th>
                            <th>Indikator</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indikators as $indikator)
                            <tr>
                                <td>{{ $indikator->subKriteria->kriteria->name }}</td>
                                <td>{{ $indikator->subKriteria->kode }} - {{ $indikator->subKriteria->name }}</td>
                                <td>{{ $indikator->name }}</td>
                                <td>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button
                                            type="button"
                                            class="btn-secondary"
                                            onclick="openEditIndikatorModal(
                                                '{{ $indikator->id }}',
                                                '{{ $indikator->subKriteria->kriteria_id }}',
                                                '{{ $indikator->sub_kriteria_id }}',
                                                @js($indikator->name)
                                            )"
                                        >
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.indikators.destroy', $indikator->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Hapus indikator ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada indikator.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $indikators->links() }}
            </div>
        </div>

        <div id="indikatorModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4">
            <div class="w-full max-w-2xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 id="indikatorModalTitle" class="page-card-title">Tambah Indikator</h3>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" onclick="closeModal('indikatorModal')">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <form method="POST" id="indikatorForm" class="space-y-4 p-5">
                    @csrf
                    <input type="hidden" name="_method" id="indikator_method" value="POST">

                    <div>
                        <label class="form-label">Kriteria</label>
                        <select id="indikator_kriteria_id" class="form-control" required>
                            <option value="">-- Pilih Kriteria --</option>
                            @foreach ($kriterias as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Kompetensi</label>
                        <select name="sub_kriteria_id" id="indikator_sub_id" class="form-control" required>
                            <option value="">-- Pilih Kompetensi --</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Indikator</label>
                        <textarea name="name" id="indikator_name" rows="4" class="form-control" required></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" class="btn-secondary" onclick="closeModal('indikatorModal')">Batal</button>
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

        function loadSubKriterias(kriteriaId, selectedSubId = '') {
            const subDropdown = document.getElementById('indikator_sub_id');
            subDropdown.innerHTML = '<option value="">-- Pilih Kompetensi --</option>';
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

        function openCreateIndikatorModal() {
            document.getElementById('indikatorModalTitle').textContent = 'Tambah Indikator';
            document.getElementById('indikatorForm').action = '{{ route('admin.indikators.store') }}';
            document.getElementById('indikator_method').value = 'POST';
            document.getElementById('indikator_kriteria_id').value = '';
            document.getElementById('indikator_name').value = '';
            loadSubKriterias('');
            openModal('indikatorModal');
        }

        function openEditIndikatorModal(id, kriteriaId, subId, name) {
            document.getElementById('indikatorModalTitle').textContent = 'Edit Indikator';
            document.getElementById('indikatorForm').action = `/admin/indikators/${id}`;
            document.getElementById('indikator_method').value = 'PUT';
            document.getElementById('indikator_kriteria_id').value = kriteriaId;
            document.getElementById('indikator_name').value = name;
            loadSubKriterias(kriteriaId, subId).then(() => openModal('indikatorModal'));
        }

        document.getElementById('indikator_kriteria_id').addEventListener('change', function() {
            loadSubKriterias(this.value);
        });
    </script>
@endsection
