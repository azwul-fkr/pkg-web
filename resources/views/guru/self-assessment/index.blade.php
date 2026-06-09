@extends('layouts.app')

@section('title', 'Self Assessment')

@section('content')
    <div class="page-card">
        <div class="page-card-header">
            <div>
                <h3 class="page-card-title">Self Assessment</h3>
                <p class="page-card-subtitle">Riwayat penilaian mandiri berdasarkan periode.</p>
            </div>

            <button onclick="openModal('createAssessmentModal')" class="btn-primary">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Buat Self Assessment
            </button>
        </div>

        <div class="table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assessments as $assessment)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $assessment->period->name }}</td>
                            <td>
                                @if ($assessment->status == 'draft')
                                    <span class="badge badge-warning">DRAFT</span>
                                @elseif($assessment->status == 'submitted')
                                    <span class="badge badge-info">SUBMITTED</span>
                                @elseif($assessment->status == 'reviewed')
                                    <span class="badge badge-success">REVIEWED</span>
                                @else
                                    <span class="badge badge-info">{{ strtoupper($assessment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('guru.self-assessment.review', $assessment->id) }}" class="btn-secondary text-xs sm:text-sm">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-slate-500">
                                Belum ada self assessment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="createAssessmentModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl shadow-slate-950/20 ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold tracking-tight text-slate-900">Buat Self Assessment</h3>
                    <p class="mt-1 text-sm leading-6 text-slate-500">Pilih periode penilaian aktif.</p>
                </div>

                <button onclick="closeModal('createAssessmentModal')" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <form action="{{ route('guru.self-assessment.store') }}" method="POST" class="space-y-5 p-5 sm:p-6">
                @csrf

                <div>
                    <label class="form-label">Periode Penilaian</label>
                    <select name="period_id" class="form-control" required>
                        <option value="">-- Pilih Periode --</option>
                        @foreach ($periods as $period)
                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="rounded-2xl border border-cyan-100 bg-cyan-50/80 p-4">
                    <p class="text-sm leading-6 text-slate-600">
                        Setelah self assessment dibuat, sistem akan mengarahkan Anda ke halaman pengisian penilaian berdasarkan indikator kompetensi guru.
                    </p>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-1 sm:flex-row sm:justify-end">
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

@push('scripts')
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
    </script>
@endpush
