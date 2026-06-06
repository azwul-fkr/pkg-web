<?php

use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvidenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guru\SelfAssessmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Guru\SettingsController as GuruSettingsController;
use App\Http\Controllers\Penilai\DashboardController as PenilaiDashboardController;
use App\Http\Controllers\Penilai\SettingsController as PenilaiSettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= PUBLIC =================
Route::get('/', function () {

    if (auth()->check()) {

        $user = auth()->user();

        if ($user->role->name == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role->name == 'guru') {
            return redirect()->route('guru.dashboard');
        }

        if ($user->role->name == 'penilai') {
            return redirect()->route('penilai.dashboard');
        }
    }

    return redirect()->route('login');
});

// ================= AUTH (BREEZE) =================
require __DIR__ . '/auth.php';


// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    // SETTINGS
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [AdminSettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/theme', [AdminSettingsController::class, 'updateTheme'])->name('settings.updateTheme');
    Route::post('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('settings.updatePassword');

    // VALIDASI EVIDENCE
    Route::get('/evidences', [App\Http\Controllers\EvidenceController::class, 'adminIndex'])
        ->name('evidences.index');

    Route::post('/evidences/{id}/approve', [App\Http\Controllers\EvidenceController::class, 'approve'])
        ->name('evidences.approve');

    Route::post('/evidences/{id}/reject', [App\Http\Controllers\EvidenceController::class, 'reject'])->name('evidences.reject');


    // MANAJEMEN USER
    Route::resource(
        'users',
        App\Http\Controllers\Admin\UserController::class
    );

    // MANAJEMEN GURU
    Route::resource(
        'gurus',
        App\Http\Controllers\Admin\GuruController::class
    );

    // MANAJEMEN KRITERIA
    Route::resource(
        'kriterias',
        App\Http\Controllers\Admin\KriteriaController::class
    );

    // MANAJEMEN SUB KRITERIA
    Route::resource(
        'sub-kriterias',
        App\Http\Controllers\Admin\SubKriteriaController::class
    );

    // MANAJEMEN INDIKATOR
    Route::resource(
        'indikators',
        App\Http\Controllers\Admin\IndikatorController::class
    );
    // Route untuk ambil sub kriteria berdasarkan kriteria
    Route::get(
        '/get-sub-kriterias/{kriteriaId}',
        [App\Http\Controllers\Admin\IndikatorController::class, 'getSubKriterias']
    );
    // MANAJEMEN INDIKATOR SCORE
    Route::resource(
        'indikator-scores',
        App\Http\Controllers\Admin\IndikatorScoreController::class
    );
    // Route untuk ambil indikator berdasarkan sub kriteria
    Route::get(
        '/get-indikators/{subId}',
        [App\Http\Controllers\Admin\IndikatorScoreController::class, 'getIndikators']
    );
    // MANAJEMEN JABATAN
    Route::resource(
        'jabatans',
        App\Http\Controllers\Admin\JabatanController::class
    );

    // MANAJEMEN PERIODE
    Route::get(
        'periods',
        [App\Http\Controllers\Admin\PeriodController::class, 'index']
    )->name('periods.index');

    Route::post(
        'periods',
        [App\Http\Controllers\Admin\PeriodController::class, 'store']
    )->name('periods.store');

    Route::put(
        'periods/{id}',
        [App\Http\Controllers\Admin\PeriodController::class, 'update']
    )->name('periods.update');

    Route::delete(
        'periods/{id}',
        [App\Http\Controllers\Admin\PeriodController::class, 'destroy']
    )->name('periods.destroy');

    // MANAJEMEN ASSIGNMENT
    Route::get(
        'assignments',
        [App\Http\Controllers\Admin\AssignmentController::class, 'index']
    )->name('assignments.index');

    Route::post(
        'assignments',
        [App\Http\Controllers\Admin\AssignmentController::class, 'store']
    )->name('assignments.store');

    Route::delete(
        'assignments/{id}',
        [App\Http\Controllers\Admin\AssignmentController::class, 'destroy']
    )->name('assignments.destroy');

    // MONITORING PENILAIAN
    Route::get(
        'monitoring',
        [App\Http\Controllers\Admin\MonitoringController::class, 'index']
    )->name('monitoring.index');

    Route::get(
        '/monitoring/{id}',
        [App\Http\Controllers\Admin\MonitoringController::class, 'detail']
    )->name('monitoring.detail');

    Route::post(
        '/monitoring/{id}/review',
        [App\Http\Controllers\Admin\MonitoringController::class, 'review']
    )->name('monitoring.review');
});


// ================= GURU =================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {

    Route::get(
        '/dashboard',
        [App\Http\Controllers\Guru\DashboardController::class, 'index']
    )->name('guru.dashboard');

    // SETTINGS
    Route::get('/settings', [GuruSettingsController::class, 'index'])->name('guru.settings.index');
    Route::post('/settings/biodata', [GuruSettingsController::class, 'updateBiodata'])->name('guru.settings.updateBiodata');
    Route::post('/settings/photo', [GuruSettingsController::class, 'uploadPhoto'])->name('guru.settings.uploadPhoto');
    Route::post('/settings/theme', [GuruSettingsController::class, 'updateTheme'])->name('guru.settings.updateTheme');
    Route::post('/settings/achievement', [GuruSettingsController::class, 'addAchievement'])->name('guru.settings.addAchievement');
    Route::delete('/settings/achievement/{achievement_id}', [GuruSettingsController::class, 'deleteAchievement'])->name('guru.settings.deleteAchievement');
    Route::post('/settings/certification', [GuruSettingsController::class, 'addCertification'])->name('guru.settings.addCertification');
    Route::delete('/settings/certification/{certification_id}', [GuruSettingsController::class, 'deleteCertification'])->name('guru.settings.deleteCertification');

    Route::get(
        '/evidence',
        [EvidenceController::class, 'index']
    )->name('guru.evidence.index');

    Route::post(
        '/evidence',
        [EvidenceController::class, 'store']
    )->name('guru.evidence.store');

    Route::get(
        '/evidence/{id}',
        [EvidenceController::class, 'show']
    )->name('guru.evidence.show');

    Route::put(
        '/evidence/{id}',
        [EvidenceController::class, 'update']
    )->name('guru.evidence.update');

    Route::delete(
        '/evidence/{id}',
        [EvidenceController::class, 'destroy']
    )->name('guru.evidence.destroy');


    Route::get('/self-assessment', [App\Http\Controllers\Guru\SelfAssessmentController::class, 'index'])->name('guru.self-assessment.index');

    Route::get(
        '/self-assessment/create',
        [App\Http\Controllers\Guru\SelfAssessmentController::class, 'create']
    )->name('guru.self-assessment.create');

    Route::post(
        '/self-assessment/store',
        [App\Http\Controllers\Guru\SelfAssessmentController::class, 'store']
    )->name('guru.self-assessment.store');

    Route::get(
        '/self-assessment/review/{id}',
        [App\Http\Controllers\Guru\SelfAssessmentController::class, 'review']
    )->name('guru.self-assessment.review');

    Route::post(
        '/self-assessment/final-submit/{id}',
        [App\Http\Controllers\Guru\SelfAssessmentController::class, 'finalSubmit']
    )->name('guru.self-assessment.final-submit');

    Route::post(
        '/reflection/store/{evaluationId}',
        [App\Http\Controllers\Guru\ReflectionController::class, 'store']
    )->name('guru.reflection.store');

    Route::put(
        '/self-assessment/{id}',
        [SelfAssessmentController::class, 'update']
    )->name('guru.self-assessment.update');
});


// ================= PENILAI =================
Route::middleware(['auth', 'role:penilai'])->prefix('penilai')->group(function () {

    Route::get('/dashboard', [PenilaiDashboardController::class, 'index'])->name('penilai.dashboard');

    // SETTINGS
    Route::get('/settings', [PenilaiSettingsController::class, 'index'])->name('penilai.settings.index');
    Route::post('/settings/profile', [PenilaiSettingsController::class, 'updateProfile'])->name('penilai.settings.updateProfile');
    Route::post('/settings/theme', [PenilaiSettingsController::class, 'updateTheme'])->name('penilai.settings.updateTheme');
    Route::post('/settings/password', [PenilaiSettingsController::class, 'updatePassword'])->name('penilai.settings.updatePassword');

    Route::get('/guru', [App\Http\Controllers\EvaluationController::class, 'guruList'])
        ->name('penilai.guru.index');

    Route::get('/guru/{id}/nilai', [App\Http\Controllers\EvaluationController::class, 'create'])
        ->name('penilai.penilaian.create');

    Route::post('/guru/{id}/nilai', [App\Http\Controllers\EvaluationController::class, 'store'])
        ->name('penilai.penilaian.store');

    Route::get('/hasil', [App\Http\Controllers\EvaluationController::class, 'hasil'])
        ->name('penilai.hasil');
    Route::get('/hasil/{id}', [App\Http\Controllers\EvaluationController::class, 'detail'])->name('penilai.hasil.detail');
    Route::get(
        '/hasil/export/pdf',
        [App\Http\Controllers\EvaluationController::class, 'exportPdf']
    )->name('penilai.hasil.pdf');

    Route::get(
        '/penilaian/{id}/edit',
        [EvaluationController::class, 'edit']
    )->name('penilai.penilaian.edit');

    Route::get(
        '/penilaian/{id}/review',
        [EvaluationController::class, 'review']
    )->name('penilai.penilaian.review');

    Route::post(
        '/penilaian/{id}/final-submit',
        [EvaluationController::class, 'finalSubmit']
    )->name('penilai.penilaian.final-submit');

    Route::put(
        '/penilaian/{id}',
        [EvaluationController::class, 'update']
    )->name('penilai.penilaian.update');
});


// ================= PROFILE (BREEZE DEFAULT) =================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
