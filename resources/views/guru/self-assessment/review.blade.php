@extends('layouts.app')

@section('title', 'Review Self Assessment')

@section('content')
    <form action="{{ route('guru.self-assessment.update', $assessment->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="page-card overflow-hidden">
            <div class="page-card-header">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Self Assessment</h1>
                    <p class="mt-1 text-sm leading-6 text-slate-500">{{ $assessment->period->name }}</p>
                </div>

                <span class="badge {{ $assessment->status == 'draft' ? 'badge-warning' : 'badge-info' }}">
                    {{ strtoupper($assessment->status) }}
                </span>
            </div>
        </div>

        @foreach ($kriterias as $kriteria)
            <div class="page-card overflow-hidden">
                <div class="border-b border-slate-200/80 bg-slate-50/70 px-5 py-4 sm:px-6">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">{{ $kriteria->name }}</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Bobot: {{ $kriteria->bobot }}%</p>
                </div>

                <div class="space-y-8 p-5 sm:p-6">
                    @foreach ($kriteria->subKriterias as $sub)
                        <div>
                            <div class="mb-4">
                                <h3 class="text-base font-semibold tracking-tight text-slate-900">{{ $sub->name }}</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-500">Bobot: {{ $sub->bobot }}%</p>
                            </div>

                            <div class="space-y-5">
                                @foreach ($sub->indikators as $indikator)
                                    @php
                                        $existing = $assessment->scores->where('indikator_id', $indikator->id)->first();
                                    @endphp

                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                        <h4 class="text-sm font-semibold leading-6 text-slate-900 sm:text-base">
                                            {{ $indikator->name }}
                                        </h4>

                                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                                            @foreach ($indikator->indikatorScores as $score)
                                                <label class="cursor-pointer">
                                                    <input type="radio" name="scores[{{ $indikator->id }}]" value="{{ $score->score }}" class="score-radio sr-only" {{ optional($existing)->nilai == $score->score ? 'checked' : '' }}>
                                                    <div class="score-card">
                                                        <div class="text-2xl font-semibold tracking-tight">{{ $score->score }}</div>
                                                        <div class="mt-1 text-xs font-medium leading-5">{{ $score->description }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <div class="mt-4">
                                            <textarea name="comments[{{ $indikator->id }}]" rows="3" placeholder="Komentar tambahan..." class="form-control text-sm">{{ optional($existing)->comment }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="sticky bottom-0 z-20 -mx-4 border-t border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:-mx-6 sm:px-6 lg:-mx-8">
            <div class="mx-auto flex max-w-[1600px] justify-end gap-3">
                <button type="submit" name="submit_type" value="draft" class="btn-secondary">
                    Simpan Draft
                </button>
                <button type="submit" name="submit_type" value="submit" class="btn-primary">
                    Submit Assessment
                </button>
            </div>
        </div>
    </form>

    <style>
        .score-card {
            width: 100%;
            border: 1px solid rgb(226 232 240);
            border-radius: 1.25rem;
            padding: 1rem 0.875rem;
            text-align: center;
            background: #fff;
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
            color: rgb(51 65 85);
        }

        .score-card:hover {
            border-color: rgb(6 182 212);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(6, 182, 212, .10);
        }

        .score-radio:checked + .score-card {
            background: rgb(6 182 212);
            border-color: rgb(6 182 212);
            color: white;
            box-shadow: 0 12px 30px rgba(6, 182, 212, .20);
        }
    </style>
@endsection
