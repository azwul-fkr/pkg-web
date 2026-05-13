@extends('layouts.app')

@section('title', 'Review Self Assessment')

@section('content')

    <form action="{{ route('guru.self-assessment.update', $assessment->id) }}" method="POST" class="space-y-6">

        @csrf

        @method('PUT')

        {{-- =====================================================
        HEADER
    ====================================================== --}}
        <div
            class="
        bg-white
        rounded-3xl
        border
        border-slate-200
        p-6
        flex
        items-center
        justify-between
    ">

            <div>

                <h1 class="
                text-2xl
                font-bold
                text-slate-800
            ">
                    Self Assessment
                </h1>

                <p class="
                text-slate-500
                mt-1
            ">
                    {{ $assessment->period->name }}
                </p>

            </div>

            <div>

                @if ($assessment->status == 'draft')
                    <span class="badge badge-warning">
                        DRAFT
                    </span>
                @else
                    <span class="badge badge-info">
                        SUBMITTED
                    </span>
                @endif

            </div>

        </div>

        {{-- =====================================================
        KRITERIA
    ====================================================== --}}
        @foreach ($kriterias as $kriteria)
            <div
                class="
            bg-white
            rounded-3xl
            border
            border-slate-200
            overflow-hidden
        ">

                {{-- HEADER --}}
                <div
                    class="
                px-6
                py-5
                border-b
                border-slate-100
                bg-slate-50
            ">

                    <h2
                        class="
                    text-lg
                    font-bold
                    text-slate-800
                ">
                        {{ $kriteria->name }}
                    </h2>

                    <p
                        class="
                    text-sm
                    text-slate-500
                    mt-1
                ">
                        Bobot:
                        {{ $kriteria->bobot }}%
                    </p>

                </div>

                {{-- CONTENT --}}
                <div class="
                p-6
                space-y-8
            ">

                    {{-- SUB KRITERIA --}}
                    @foreach ($kriteria->subKriterias as $sub)
                        <div>

                            {{-- SUB TITLE --}}
                            <div class="mb-5">

                                <h3
                                    class="
                                text-base
                                font-semibold
                                text-slate-800
                            ">
                                    {{ $sub->name }}
                                </h3>

                                <p
                                    class="
                                text-sm
                                text-slate-500
                                mt-1
                            ">
                                    Bobot:
                                    {{ $sub->bobot }}%
                                </p>

                            </div>

                            {{-- INDIKATOR --}}
                            <div class="space-y-5">

                                @foreach ($sub->indikators as $indikator)
                                    @php

                                        $existing = $assessment->scores->where('indikator_id', $indikator->id)->first();

                                    @endphp

                                    <div
                                        class="
                                    border
                                    border-slate-200
                                    rounded-2xl
                                    p-5
                                ">

                                        {{-- TITLE --}}
                                        <div>

                                            <h4
                                                class="
                                            font-medium
                                            text-slate-800
                                            leading-relaxed
                                        ">
                                                {{ $indikator->name }}
                                            </h4>

                                        </div>

                                        {{-- =====================================================
                                        SCORE
                                    ====================================================== --}}
                                        <div
                                            class="
                                        flex
                                        items-center
                                        gap-4
                                        mt-5
                                        flex-wrap
                                    ">

                                            @foreach ($indikator->indikatorScores as $score)
                                                <label
                                                    class="
                                                cursor-pointer
                                            ">

                                                    <input type="radio" name="scores[{{ $indikator->id }}]"
                                                        value="{{ $score->score }}" class="hidden score-radio"
                                                        {{ optional($existing)->nilai == $score->score ? 'checked' : '' }}>

                                                    <div
                                                        class="
                                                    score-card
                                                ">

                                                        <div
                                                            class="
                                                        text-xl
                                                        font-bold
                                                    ">
                                                            {{ $score->score }}
                                                        </div>

                                                        <div
                                                            class="
                                                        text-xs
                                                        mt-1
                                                        font-medium
                                                    ">
                                                            {{ $score->description }}
                                                        </div>

                                                    </div>

                                                </label>
                                            @endforeach

                                        </div>

                                        {{-- =====================================================
                                        COMMENT
                                    ====================================================== --}}
                                        <div class="mt-5">

                                            <textarea name="comments[{{ $indikator->id }}]" rows="3" placeholder="Komentar tambahan..." class="form-control">{{ optional($existing)->comment }}</textarea>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

        {{-- =====================================================
        ACTION
    ====================================================== --}}
        <div class="
        sticky
        bottom-0
        bg-[#F8FAFC]
        py-5
    ">

            <div class="
            flex
            justify-end
            gap-3
        ">

                <button type="submit" name="submit_type" value="draft" class="btn-secondary">

                    Simpan Draft

                </button>

                <button type="submit" name="submit_type" value="submit" class="btn-primary">

                    Submit Assessment

                </button>

            </div>

        </div>

    </form>

    {{-- =====================================================
    STYLE
====================================================== --}}
    <style>
        /*
        =====================================================
        SCORE CARD
        =====================================================
        */

        .score-card {

            width: 110px;

            border: 2px solid #E2E8F0;

            border-radius: 20px;

            padding: 18px 14px;

            text-align: center;

            background: white;

            transition: .2s ease;

            color: #334155;
        }

        /*
        =====================================================
        HOVER
        =====================================================
        */

        .score-card:hover {

            border-color: #06B6D4;

            transform: translateY(-2px);

            box-shadow:
                0 10px 25px rgba(6, 182, 212, .10);
        }

        /*
        =====================================================
        ACTIVE
        =====================================================
        */

        .score-radio:checked+.score-card {

            background: #06B6D4;

            border-color: #06B6D4;

            color: white;

            box-shadow:
                0 12px 30px rgba(6, 182, 212, .20);
        }
    </style>

@endsection
