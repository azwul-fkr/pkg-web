@extends('layouts.app')

@section('title', 'Dashboard Penilai')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2">
        <a href="{{ route('penilai.guru.index') }}" class="page-card p-5 transition hover:border-cyan-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-cyan-50 text-cyan-700">
                    <i data-lucide="users" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Daftar Guru</h3>
                    <p class="text-sm text-slate-500">Lihat guru yang perlu dinilai.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('penilai.hasil') }}" class="page-card p-5 transition hover:border-cyan-300 hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                    <i data-lucide="bar-chart-3" class="h-5 w-5"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Hasil Penilaian</h3>
                    <p class="text-sm text-slate-500">Pantau nilai dan ranking guru.</p>
                </div>
            </div>
        </a>
    </div>
@endsection
