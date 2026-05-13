@extends('layouts.app')

@section('title', 'Detail Evidence')

@section('content')
    <div class="page-card max-w-4xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Detail Evidence</h3>
                <p class="page-card-subtitle">Ringkasan evidence dan status validasinya.</p>
            </div>

            <span
                class="badge {{ $evidence->status == 'approved' ? 'badge-success' : ($evidence->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                {{ strtoupper($evidence->status) }}
            </span>
        </div>

        <div class="grid gap-5 p-5 sm:grid-cols-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kriteria</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $evidence->kriteria?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kompetensi</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $evidence->subKriteria?->name ?? '-' }}</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Indikator</p>
                <p class="mt-1 font-semibold text-slate-900">{{ $evidence->indikator?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Mata Pelajaran</p>
                <p class="mt-1 text-slate-800">{{ $evidence->subject }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Kelas</p>
                <p class="mt-1 text-slate-800">{{ $evidence->kelas }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Tanggal</p>
                <p class="mt-1 text-slate-800">{{ $evidence->tanggal }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">File Evidence</p>
                @php

                    $extension = strtolower(pathinfo($evidence->file, PATHINFO_EXTENSION));

                @endphp

                {{-- =====================================================
    PREVIEW FILE
====================================================== --}}
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

                    {{-- =====================================================
    DOWNLOAD FILE
====================================================== --}}
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
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Deskripsi</p>
                <p class="mt-1 rounded-lg bg-slate-50 p-4 text-slate-700">{{ $evidence->description }}</p>
            </div>
        </div>

        <div class="border-t border-slate-200 px-5 py-4">
            <a href="{{ route('guru.evidence.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
