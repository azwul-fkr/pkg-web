@extends('layouts.app')

@section('title', 'Edit Penilaian Guru')

@section('content')

    <div class="space-y-6">

        {{-- =====================================================
        HEADER
    ====================================================== --}}
        <div class="page-card p-6">

            <div
                class="
            flex
            flex-col
            lg:flex-row
            lg:items-center
            lg:justify-between
            gap-6
        ">

                {{-- LEFT --}}
                <div>

                    <div
                        class="
                    inline-flex
                    items-center
                    gap-2
                    px-3
                    py-1
                    rounded-full
                    bg-cyan-100
                    text-cyan-700
                    text-sm
                    font-medium
                    mb-4
                ">

                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>

                        Penilaian Guru

                    </div>

                    <h2
                        class="
                    text-3xl
                    font-bold
                    text-slate-900
                ">

                        {{ $evaluation->guru->user->name }}

                    </h2>

                    <div
                        class="
                    mt-3
                    flex
                    flex-wrap
                    items-center
                    gap-4
                    text-sm
                    text-slate-500
                ">

                        <span class="flex items-center gap-2">

                            <i data-lucide="book-open" class="w-4 h-4"></i>

                            {{ $evaluation->guru->subject ?? '-' }}

                        </span>

                        <span class="flex items-center gap-2">

                            <i data-lucide="briefcase" class="w-4 h-4"></i>

                            {{ $evaluation->guru->jabatan->name ?? '-' }}

                        </span>

                        <span class="flex items-center gap-2">

                            <i data-lucide="calendar" class="w-4 h-4"></i>

                            {{ $evaluation->period->name }}

                        </span>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div
                    class="
                w-full
                lg:w-80
                bg-slate-50
                border
                border-slate-200
                rounded-3xl
                p-5
            ">

                    <div
                        class="
                    flex
                    items-center
                    justify-between
                    mb-3
                ">

                        <span
                            class="
                        text-sm
                        font-medium
                        text-slate-600
                    ">

                            Progress Penilaian

                        </span>

                        <span
                            class="
                        text-sm
                        font-bold
                        text-cyan-600
                    ">

                            {{ $progressPercentage }}%

                        </span>

                    </div>

                    <div
                        class="
                    w-full
                    h-3
                    bg-slate-200
                    rounded-full
                    overflow-hidden
                ">

                        <div class="
                            h-full
                            rounded-full
                            bg-gradient-to-r
                            from-cyan-500
                            to-blue-500
                        "
                            style="
                            width:
                            {{ $progressPercentage }}%
                        ">
                        </div>

                    </div>

                    <div
                        class="
                    mt-3
                    text-sm
                    text-slate-500
                ">

                        {{ $completedIndikator }}
                        dari
                        {{ $totalIndikator }}
                        indikator selesai

                    </div>

                </div>

            </div>

        </div>

        {{-- =====================================================
        REVISI
    ====================================================== --}}
        @if ($evaluation->status == 'revised')
            <div
                class="
            rounded-3xl
            border
            border-red-200
            bg-red-50
            p-5
        ">

                <div class="
                flex
                items-start
                gap-3
            ">

                    <i data-lucide="alert-triangle"
                        class="
                        w-5
                        h-5
                        text-red-600
                        mt-0.5
                    "></i>

                    <div>

                        <h3
                            class="
                        font-bold
                        text-red-800
                    ">

                            Catatan Revisi Admin

                        </h3>

                        <p
                            class="
                        mt-2
                        text-sm
                        text-red-700
                    ">

                            {{ $evaluation->revision_note }}

                        </p>

                    </div>

                </div>

            </div>
        @endif

        {{-- =====================================================
        KRITERIA PROGRESS
    ====================================================== --}}
        <div class="
        grid
        grid-cols-1
        md:grid-cols-2
        xl:grid-cols-4
        gap-5
    ">

            @foreach ($kriteriaProgress as $item)
                <div class="page-card p-5">

                    <div
                        class="
                    flex
                    items-start
                    justify-between
                    mb-4
                ">

                        <div>

                            <h3
                                class="
                            font-semibold
                            text-slate-800
                            mb-1
                        ">

                                {{ $item['name'] }}

                            </h3>

                            <p
                                class="
                            text-sm
                            text-slate-500
                        ">

                                {{ $item['completed'] }}
                                /
                                {{ $item['total'] }}
                                indikator

                            </p>

                        </div>

                        <div
                            class="
                        w-11
                        h-11
                        rounded-2xl
                        bg-cyan-100
                        text-cyan-600
                        flex
                        items-center
                        justify-center
                    ">

                            <i data-lucide="bar-chart-3" class="w-5 h-5"></i>

                        </div>

                    </div>

                    <div
                        class="
                    w-full
                    h-2
                    bg-slate-200
                    rounded-full
                    overflow-hidden
                ">

                        <div class="
                            h-full
                            bg-cyan-500
                            rounded-full
                        "
                            style="
                            width:
                            {{ $item['percentage'] }}%
                        ">
                        </div>

                    </div>

                    <div
                        class="
                    mt-3
                    text-right
                    text-sm
                    font-semibold
                    text-cyan-600
                ">

                        {{ $item['percentage'] }}%

                    </div>

                </div>
            @endforeach

        </div>

        {{-- =====================================================
        EVIDENCE
    ====================================================== --}}
        <div class="page-card">

            <div class="page-card-header">

                <div>

                    <h3 class="page-card-title">
                        Evidence Guru
                    </h3>

                    <p class="page-card-subtitle">
                        Bukti pendukung penilaian guru.
                    </p>

                </div>

            </div>

            <div class="
            grid
            gap-5
            p-5
            md:grid-cols-2
        ">

                @forelse ($evidences as $evidence)

                    <div
                        class="
                    rounded-3xl
                    border
                    border-slate-200
                    bg-slate-50
                    p-5
                ">

                        <div
                            class="
                        grid
                        gap-2
                        text-sm
                        text-slate-600
                    ">

                            <p>

                                <span
                                    class="
                                font-semibold
                                text-slate-800
                            ">
                                    Mata Pelajaran:
                                </span>

                                {{ $evidence->subject }}

                            </p>

                            <p>

                                <span
                                    class="
                                font-semibold
                                text-slate-800
                            ">
                                    Kelas:
                                </span>

                                {{ $evidence->kelas }}

                            </p>

                            <p>

                                <span
                                    class="
                                font-semibold
                                text-slate-800
                            ">
                                    Tanggal:
                                </span>

                                {{ $evidence->tanggal }}

                            </p>

                            <p class="pt-2">

                                {{ $evidence->description }}

                            </p>

                        </div>

                        @if (!empty($evidence->file))
                            <div class="mt-4">

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
                                    ">

                                        <i data-lucide="download" class="w-4 h-4"></i>

                                        Download File

                                    </a>
                                @endif

                            </div>
                        @endif

                    </div>

                @empty

                    <div
                        class="
                    md:col-span-2
                    text-center
                    py-10
                    text-slate-500
                ">

                        Belum ada evidence.

                    </div>

                @endforelse

            </div>

        </div>

        {{-- =====================================================
        FORM PENILAIAN
    ====================================================== --}}
        <form action="{{ route('penilai.penilaian.update', $evaluation->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach ($kriterias as $kriteria)
                <section class="page-card">

                    <div class="page-card-header">

                        <div>

                            <h3 class="page-card-title">

                                {{ $kriteria->name }}

                            </h3>

                            <p class="page-card-subtitle">

                                Bobot:
                                {{ $kriteria->bobot }}%

                            </p>

                        </div>

                    </div>

                    <div class="
                    space-y-6
                    p-5
                ">

                        @foreach ($kriteria->subKriterias as $sub)
                            <div
                                class="
                            rounded-3xl
                            border
                            border-slate-200
                            p-5
                        ">

                                <div
                                    class="
                                flex
                                flex-col
                                gap-2
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                                mb-5
                            ">

                                    <h4
                                        class="
                                    text-lg
                                    font-bold
                                    text-slate-800
                                ">

                                        {{ $sub->name }}

                                    </h4>

                                    <span class="badge badge-info">

                                        Bobot
                                        {{ $sub->bobot }}%

                                    </span>

                                </div>

                                <div class="space-y-5">

                                    @foreach ($sub->indikators as $indikator)
                                        @php

                                            $existingScore = $evaluation->scores
                                                ->where('indikator_id', $indikator->id)
                                                ->first();

                                        @endphp

                                        <div
                                            class="
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        bg-slate-50
                                        p-5
                                    ">

                                            <h5
                                                class="
                                            font-semibold
                                            text-slate-900
                                            mb-5
                                        ">

                                                {{ $indikator->name }}

                                            </h5>

                                            {{-- SCORE --}}
                                            <div class="
    flex
    flex-wrap
    gap-3
    mb-5
">

                                                @foreach ($indikator->indikatorScores as $rubrik)
                                                    <label
                                                        class="
            relative
            cursor-pointer
        ">

                                                        <input type="radio" name="scores[{{ $indikator->id }}]"
                                                            value="{{ $rubrik->score }}" class="peer absolute opacity-0"
                                                            {{ optional($existingScore)->nilai == $rubrik->score ? 'checked' : '' }}
                                                            required>

                                                        <div
                                                            class="
                relative
                min-w-[120px]
                px-5
                py-4
                rounded-2xl
                border-2
                border-slate-200
                bg-white
                text-slate-700
                transition-all
                duration-200

                hover:border-cyan-400
                hover:shadow-md

                peer-checked:border-cyan-500
                peer-checked:bg-cyan-500
                peer-checked:text-white
                peer-checked:shadow-lg
                peer-checked:ring-4
                peer-checked:ring-cyan-100
            ">

                                                            {{-- CHECK ICON --}}
                                                            <div
                                                                class="
                    absolute
                    top-2
                    right-2
                    hidden
                    peer-checked:block
                ">

                                                                <i data-lucide="check-circle-2" class="w-5 h-5"></i>

                                                            </div>

                                                            {{-- SCORE --}}
                                                            <div
                                                                class="
                    text-xl
                    font-bold
                    text-center
                ">

                                                                {{ $rubrik->score }}

                                                            </div>

                                                            {{-- DESCRIPTION --}}
                                                            <div
                                                                class="
                    text-xs
                    text-center
                    mt-2
                    leading-relaxed
                ">

                                                                {{ $rubrik->description }}

                                                            </div>

                                                        </div>

                                                    </label>
                                                @endforeach

                                            </div>

                                            {{-- COMMENT --}}
                                            <div>

                                                <label class="form-label">

                                                    Komentar Penilai

                                                </label>

                                                <textarea name="comments[{{ $indikator->id }}]" rows="3" class="form-control"
                                                    placeholder="Tambahkan komentar...">{{ optional($existingScore)->comment }}</textarea>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        @endforeach

                    </div>

                </section>
            @endforeach

            {{-- ACTION --}}
            <div class="
            flex
            flex-wrap
            justify-end
            gap-3
        ">

                @if (isset($evaluation))
                    <a href="{{ route('penilai.penilaian.review', $evaluation->id) }}" class="btn-secondary">

                        <i data-lucide="eye" class="w-4 h-4"></i>

                        Review

                    </a>
                @endif

                <button type="submit" name="action" value="draft" class="btn-secondary">

                    <i data-lucide="save" class="w-4 h-4"></i>

                    Simpan Draft

                </button>

                <button type="submit" name="action" value="submit" class="btn-primary">

                    <i data-lucide="send" class="w-4 h-4"></i>

                    Submit Penilaian

                </button>

            </div>

        </form>

    </div>

@endsection
