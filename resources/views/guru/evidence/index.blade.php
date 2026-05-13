@extends('layouts.app')

@section('title', 'Data Evidence')

@section('content')

    <div class="space-y-6">

        {{-- =====================================================
        HEADER
    ====================================================== --}}
        <div class="page-card">

            <div class="page-card-header">

                <div>

                    <h3 class="page-card-title">
                        Data Evidence
                    </h3>

                    <p class="page-card-subtitle">
                        Daftar evidence yang sudah Anda upload.
                    </p>

                </div>

                <button type="button" onclick="openModal('createEvidenceModal')" class="btn-primary">

                    <i data-lucide="upload" class="w-4 h-4"></i>

                    Upload Evidence

                </button>

            </div>

        </div>

        {{-- =====================================================
        TABLE
    ====================================================== --}}
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

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    @php

                                        $extension = strtolower(pathinfo($evidence->file, PATHINFO_EXTENSION));

                                    @endphp

                                    @if (in_array($extension, ['pdf', 'jpg', 'jpeg', 'png']))
                                        <a href="{{ asset('storage/' . $evidence->file) }}" target="_blank"
                                            class="
            inline-flex
            items-center
            gap-2
            text-cyan-600
            hover:text-cyan-700
            font-medium
            transition
        ">

                                            <i data-lucide="eye" class="w-4 h-4"></i>

                                            Lihat File

                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $evidence->file) }}" download
                                            class="
            inline-flex
            items-center
            gap-2
            text-emerald-600
            hover:text-emerald-700
            font-medium
            transition
        ">

                                            <i data-lucide="download" class="w-4 h-4"></i>

                                            Download File

                                        </a>
                                    @endif

                                </td>

                                <td>
                                    {{ $evidence->kriteria?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $evidence->subKriteria?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $evidence->indikator?->name ?? '-' }}
                                </td>

                                <td>

                                    @if ($evidence->status == 'approved')
                                        <span class="badge badge-success">
                                            APPROVED
                                        </span>
                                    @elseif($evidence->status == 'rejected')
                                        <span class="badge badge-danger">
                                            REJECTED
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            PENDING
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $evidence->tanggal }}
                                </td>

                                <td>

                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('guru.evidence.show', $evidence->id) }}" class="btn-secondary">

                                            Detail

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-10 text-slate-500">

                                    Belum ada evidence.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- =====================================================
    MODAL CREATE
====================================================== --}}
    <div id="createEvidenceModal"
        class="
        fixed
        inset-0
        z-50
        hidden
        items-center
        justify-center
        bg-black/50
        backdrop-blur-sm
        p-4
    ">

        <div
            class="
        w-full
        max-w-4xl
        bg-white
        rounded-3xl
        shadow-2xl
        overflow-hidden
    ">

            {{-- HEADER --}}
            <div
                class="
            flex
            items-center
            justify-between
            px-6
            py-5
            border-b
            border-slate-200
        ">

                <div>

                    <h3
                        class="
                    text-xl
                    font-bold
                    text-slate-800
                ">
                        Upload Evidence
                    </h3>

                    <p
                        class="
                    text-sm
                    text-slate-500
                    mt-1
                ">
                        Upload dokumen pendukung penilaian guru.
                    </p>

                </div>

                <button type="button" onclick="closeModal('createEvidenceModal')"
                    class="
                    w-10
                    h-10
                    rounded-xl
                    hover:bg-slate-100
                    transition
                ">

                    ✕

                </button>

            </div>

            {{-- FORM --}}
            <form action="{{ route('guru.evidence.store') }}" method="POST" enctype="multipart/form-data"
                class="
                p-6
                grid
                grid-cols-1
                md:grid-cols-2
                gap-5
            ">

                @csrf

                {{-- SUBJECT --}}
                <div>

                    <label class="form-label">
                        Mata Pelajaran
                    </label>

                    <input type="text" name="subject" class="form-control" required>

                </div>

                {{-- KELAS --}}
                <div>

                    <label class="form-label">
                        Kelas
                    </label>

                    <input type="text" name="kelas" class="form-control" required>

                </div>

                {{-- TANGGAL --}}
                <div>

                    <label class="form-label">
                        Tanggal
                    </label>

                    <input type="date" name="tanggal" class="form-control" required>

                </div>

                {{-- KRITERIA --}}
                <div>

                    <label class="form-label">
                        Kriteria
                    </label>

                    <select name="kriteria_id" id="kriteriaSelect" class="form-control" required>

                        <option value="">
                            -- Pilih Kriteria --
                        </option>

                        @foreach ($kriterias as $kriteria)
                            <option value="{{ $kriteria->id }}">

                                {{ $kriteria->name }}

                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- SUB KRITERIA --}}
                <div>

                    <label class="form-label">
                        Kompetensi
                    </label>

                    <select name="sub_kriteria_id" id="subSelect" class="form-control" required>

                        <option value="">
                            -- Pilih Kompetensi --
                        </option>

                    </select>

                </div>

                {{-- INDIKATOR --}}
                <div>

                    <label class="form-label">
                        Indikator
                    </label>

                    <select name="indikator_id" id="indikatorSelect" class="form-control" required>

                        <option value="">
                            -- Pilih Indikator --
                        </option>

                    </select>

                </div>

                {{-- DESKRIPSI --}}
                <div class="md:col-span-2">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea name="description" rows="4" class="form-control" required></textarea>

                </div>

                {{-- FILE --}}
                <div class="md:col-span-2">

                    <label class="form-label">
                        File Evidence
                    </label>

                    <input type="file" name="file" class="form-control" required>

                </div>

                {{-- ACTION --}}
                <div
                    class="
                md:col-span-2
                flex
                justify-end
                gap-3
                pt-2
            ">

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

    {{-- =====================================================
    SCRIPT
====================================================== --}}
    <script>
        /*
        =====================================================
        MODAL
        =====================================================
        */

        function openModal(id) {

            document
                .getElementById(id)
                .classList
                .remove('hidden');

            document
                .getElementById(id)
                .classList
                .add('flex');
        }

        function closeModal(id) {

            document
                .getElementById(id)
                .classList
                .add('hidden');

            document
                .getElementById(id)
                .classList
                .remove('flex');
        }

        /*
        =====================================================
        DROPDOWN
        =====================================================
        */

        const kriterias =
            @json($kriterias);

        const kriteriaSelect =
            document.getElementById(
                'kriteriaSelect'
            );

        const subSelect =
            document.getElementById(
                'subSelect'
            );

        const indikatorSelect =
            document.getElementById(
                'indikatorSelect'
            );

        /*
        =====================================================
        KRITERIA CHANGE
        =====================================================
        */

        kriteriaSelect.addEventListener(
            'change',
            function() {

                const selected =
                    kriterias.find(
                        k => k.id == this.value
                    );

                subSelect.innerHTML =
                    '<option value="">-- Pilih Kompetensi --</option>';

                indikatorSelect.innerHTML =
                    '<option value="">-- Pilih Indikator --</option>';

                if (selected) {

                    selected.sub_kriterias.forEach(sub => {

                        subSelect.innerHTML += `
                        <option value="${sub.id}">
                            ${sub.name}
                        </option>
                    `;
                    });
                }
            }
        );

        /*
        =====================================================
        SUB KRITERIA CHANGE
        =====================================================
        */

        subSelect.addEventListener(
            'change',
            function() {

                indikatorSelect.innerHTML =
                    '<option value="">-- Pilih Indikator --</option>';

                const selectedKriteria =
                    kriterias.find(
                        k => k.id == kriteriaSelect.value
                    );

                if (!selectedKriteria) return;

                const selectedSub =
                    selectedKriteria.sub_kriterias.find(
                        s => s.id == this.value
                    );

                if (selectedSub) {

                    selectedSub.indikators.forEach(indikator => {

                        indikatorSelect.innerHTML += `
                        <option value="${indikator.id}">
                            ${indikator.name}
                        </option>
                    `;
                    });
                }
            }
        );
    </script>

@endsection
