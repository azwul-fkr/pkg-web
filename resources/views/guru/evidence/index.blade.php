@extends('layouts.app')

@section('title', 'Data Evidence')

@section('content')
    <div class="page-stack">
        <div class="page-card">
            <div class="page-card-header">
                <div>
                    <h3 class="page-card-title">Data Evidence</h3>
                    <p class="page-card-subtitle">Daftar evidence yang sudah Anda upload.</p>
                </div>

                <button type="button" onclick="openModal('createEvidenceModal')" class="btn-primary">
                    <i data-lucide="upload" class="h-4 w-4"></i>
                    Upload Evidence
                </button>
            </div>
        </div>

        <div class="page-card">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>File</th>
                            <th>Kriteria</th>
                            <th>Kompetensi</th>
                            <th>Indikator</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($evidences as $evidence)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $extension = strtolower(pathinfo($evidence->file, PATHINFO_EXTENSION));
                                    @endphp

                                    @if (in_array($extension, ['pdf', 'jpg', 'jpeg', 'png']))
                                        <a href="{{ asset('storage/' . $evidence->file) }}" target="_blank" class="link-action">
                                            <i data-lucide="eye" class="h-4 w-4"></i>
                                            Lihat File
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $evidence->file) }}" download class="link-action text-emerald-700 hover:text-emerald-900">
                                            <i data-lucide="download" class="h-4 w-4"></i>
                                            Download File
                                        </a>
                                    @endif
                                </td>
                                <td>{{ $evidence->kriteria?->name ?? '-' }}</td>
                                <td>{{ $evidence->subKriteria?->name ?? '-' }}</td>
                                <td>{{ $evidence->indikator?->name ?? '-' }}</td>
                                <td>
                                    @if ($evidence->status == 'approved')
                                        <span class="badge badge-success">APPROVED</span>
                                    @elseif($evidence->status == 'rejected')
                                        <span class="badge badge-danger">REJECTED</span>
                                    @else
                                        <span class="badge badge-warning">PENDING</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">{{ $evidence->tanggal }}</td>
                                <td>
                                    <a href="{{ route('guru.evidence.show', $evidence->id) }}" class="btn-secondary text-xs sm:text-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500">
                                    Belum ada evidence.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="createEvidenceModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm">
        <div class="w-full max-w-4xl overflow-hidden rounded-3xl bg-white shadow-2xl shadow-slate-950/20 ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
                <div>
                    <h3 class="text-xl font-semibold tracking-tight text-slate-900">Upload Evidence</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Upload dokumen pendukung penilaian guru.</p>
                </div>

                <button type="button" onclick="closeModal('createEvidenceModal')" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('guru.evidence.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-5 p-5 sm:p-6 md:grid-cols-2">
                @csrf

                <div>
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Kriteria</label>
                    <select name="kriteria_id" id="kriteriaSelect" class="form-control" required>
                        <option value="">-- Pilih Kriteria --</option>
                        @foreach ($kriterias as $kriteria)
                            <option value="{{ $kriteria->id }}">{{ $kriteria->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Kompetensi</label>
                    <select name="sub_kriteria_id" id="subSelect" class="form-control" required>
                        <option value="">-- Pilih Kompetensi --</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Indikator</label>
                    <select name="indikator_id" id="indikatorSelect" class="form-control" required>
                        <option value="">-- Pilih Indikator --</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control" required></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">File Evidence</label>
                    <input type="file" name="file" class="form-control" required>
                </div>

                <div class="md:col-span-2 flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
                    <button type="button" onclick="closeModal('createEvidenceModal')" class="btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary">
                        Upload Evidence
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        const kriterias = @json($kriterias);
        const kriteriaSelect = document.getElementById('kriteriaSelect');
        const subSelect = document.getElementById('subSelect');
        const indikatorSelect = document.getElementById('indikatorSelect');

        if (kriteriaSelect && subSelect && indikatorSelect) {
            kriteriaSelect.addEventListener('change', function () {
                const selected = kriterias.find(k => k.id == this.value);

                subSelect.innerHTML = '<option value="">-- Pilih Kompetensi --</option>';
                indikatorSelect.innerHTML = '<option value="">-- Pilih Indikator --</option>';

                if (selected) {
                    selected.sub_kriterias.forEach(sub => {
                        subSelect.insertAdjacentHTML('beforeend', `<option value="${sub.id}">${sub.name}</option>`);
                    });
                }
            });

            subSelect.addEventListener('change', function () {
                indikatorSelect.innerHTML = '<option value="">-- Pilih Indikator --</option>';

                const selectedKriteria = kriterias.find(k => k.id == kriteriaSelect.value);
                if (!selectedKriteria) return;

                const selectedSub = selectedKriteria.sub_kriterias.find(s => s.id == this.value);
                if (!selectedSub) return;

                selectedSub.indikators.forEach(indikator => {
                    indikatorSelect.insertAdjacentHTML('beforeend', `<option value="${indikator.id}">${indikator.name}</option>`);
                });
            });
        }
    </script>
@endsection
