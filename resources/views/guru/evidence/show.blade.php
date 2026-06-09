@extends('layouts.app')

@section('title', 'Detail Evidence')

@section('content')
    <div class="page-card max-w-4xl">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Detail Evidence</h3>
                <p class="page-card-subtitle">Ringkasan evidence dan status validasinya.</p>
            </div>

            <span class="badge {{ $evidence->status == 'approved' ? 'badge-success' : ($evidence->status == 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                {{ strtoupper($evidence->status) }}
            </span>
        </div>

        <div class="grid gap-4 p-5 sm:grid-cols-2 sm:gap-5">
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Kriteria</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $evidence->kriteria?->name ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Kompetensi</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $evidence->subKriteria?->name ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4 sm:col-span-2">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Indikator</p>
                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $evidence->indikator?->name ?? '-' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Mata Pelajaran</p>
                <p class="mt-2 text-sm text-slate-700">{{ $evidence->subject }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Kelas</p>
                <p class="mt-2 text-sm text-slate-700">{{ $evidence->kelas }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Tanggal</p>
                <p class="mt-2 text-sm text-slate-700">{{ $evidence->tanggal }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50/80 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">File Evidence</p>
                @php
                    $extension = strtolower(pathinfo($evidence->file, PATHINFO_EXTENSION));
                @endphp

                @if (in_array($extension, ['pdf', 'jpg', 'jpeg', 'png']))
                    <a href="{{ asset('storage/' . $evidence->file) }}" target="_blank" class="link-action mt-2">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                        Lihat File
                    </a>
                @else
                    <a href="{{ asset('storage/' . $evidence->file) }}" download class="link-action mt-2 text-emerald-700 hover:text-emerald-900">
                        <i data-lucide="download" class="h-4 w-4"></i>
                        Download File
                    </a>
                @endif
            </div>
            <div class="sm:col-span-2">
                <p class="text-[11px] font-semibold uppercase tracking-[.18em] text-slate-500">Deskripsi</p>
                <p class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-700">{{ $evidence->description }}</p>
            </div>
        </div>

        <div class="border-t border-slate-200/80 px-5 py-4">
            <a href="{{ route('guru.evidence.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
@endsection
