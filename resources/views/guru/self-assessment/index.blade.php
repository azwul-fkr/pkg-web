@extends('layouts.app')

@section('title', 'Self Assessment')

@section('content')

    {{-- =====================================================
        PAGE
    ====================================================== --}}
    <div class="page-card">

        {{-- HEADER --}}
        <div class="page-card-header">

            <div>

                <h3 class="page-card-title">
                    Self Assessment
                </h3>

                <p class="page-card-subtitle">
                    Riwayat penilaian mandiri berdasarkan periode.
                </p>

            </div>

            {{-- BUTTON MODAL --}}
            <button onclick="openModal('createAssessmentModal')" class="btn-primary">

                <i data-lucide="plus" class="h-4 w-4"></i>

                Buat Self Assessment

            </button>

        </div>

        {{-- TABLE --}}
        <div class="table-wrap">

            <table class="app-table">

                <thead>

                    <tr>

                        <th>
                            Periode
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($assessments as $assessment)
                        <tr>

                            <td
                                class="
                                font-semibold
                                text-slate-900
                            ">

                                {{ $assessment->period->name }}

                            </td>

                            <td>

                                @if ($assessment->status == 'draft')
                                    <span class="badge badge-warning">
                                        DRAFT
                                    </span>
                                @elseif($assessment->status == 'submitted')
                                    <span class="badge badge-info">
                                        SUBMITTED
                                    </span>
                                @elseif($assessment->status == 'reviewed')
                                    <span class="badge badge-success">
                                        REVIEWED
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        {{ strtoupper($assessment->status) }}
                                    </span>
                                @endif

                            </td>

                            <td>

                                <a href="{{ route('guru.self-assessment.review', $assessment->id) }}" class="btn-secondary">

                                    Review

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="
                                    text-center
                                    text-slate-500
                                ">

                                Belum ada self assessment.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- =====================================================
        MODAL CREATE
    ====================================================== --}}
    <div id="createAssessmentModal"
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
            max-w-xl
            rounded-3xl
            bg-white
            shadow-2xl
            overflow-hidden
            animate-scaleIn
        ">

            {{-- HEADER --}}
            <div
                class="
                flex
                items-center
                justify-between
                border-b
                border-slate-200
                px-6
                py-5
            ">

                <div>

                    <h3
                        class="
                        text-lg
                        font-bold
                        text-slate-800
                    ">
                        Buat Self Assessment
                    </h3>

                    <p
                        class="
                        text-sm
                        text-slate-500
                        mt-1
                    ">
                        Pilih periode penilaian aktif.
                    </p>

                </div>

                <button onclick="closeModal('createAssessmentModal')"
                    class="
                        w-10
                        h-10
                        rounded-xl
                        hover:bg-slate-100
                        flex
                        items-center
                        justify-center
                        transition
                    ">

                    <i data-lucide="x" class="w-5 h-5"></i>

                </button>

            </div>

            {{-- FORM --}}
            <form action="{{ route('guru.self-assessment.store') }}" method="POST" class="space-y-5 p-6">

                @csrf

                {{-- PERIOD --}}
                <div>

                    <label class="form-label">
                        Periode Penilaian
                    </label>

                    <select name="period_id" class="form-control" required>

                        <option value="">
                            -- Pilih Periode --
                        </option>

                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}">

                                {{ $period->name }}

                            </option>
                        @endforeach

                    </select>

                </div>

                {{-- INFO --}}
                <div
                    class="
        rounded-2xl
        bg-cyan-50
        border
        border-cyan-100
        p-4
    ">

                    <p class="
            text-sm
            text-slate-600
            leading-relaxed
        ">

                        Setelah self assessment dibuat,
                        sistem akan mengarahkan Anda
                        ke halaman pengisian penilaian
                        berdasarkan indikator kompetensi guru.

                    </p>

                </div>

                {{-- ACTION --}}
                <div class="
        flex
        justify-end
        gap-3
        pt-3
    ">

                    <button type="button" onclick="closeModal('createAssessmentModal')" class="btn-secondary">

                        Batal

                    </button>

                    <button type="submit" class="btn-primary">

                        Lanjutkan

                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection

{{-- =====================================================
    SCRIPT
====================================================== --}}
@push('scripts')
    <script>
        /*
            =====================================================
            OPEN MODAL
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

        /*
        =====================================================
        CLOSE MODAL
        =====================================================
        */

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
    </script>
@endpush
