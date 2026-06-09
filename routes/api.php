<?php

use App\Http\Controllers\Api\Guru\AuthController as GuruAuthController;
use App\Http\Controllers\Api\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Api\Guru\EvidenceController as GuruEvidenceController;
use App\Http\Controllers\Api\Guru\ReflectionController as GuruReflectionController;
use App\Http\Controllers\Api\Guru\SelfAssessmentController as GuruSelfAssessmentController;
use App\Http\Controllers\Api\Guru\SettingsController as GuruSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('guru')->group(function () {
    Route::post('/login', [GuruAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [GuruAuthController::class, 'me']);
        Route::post('/logout', [GuruAuthController::class, 'logout']);

        Route::get('/dashboard', [GuruDashboardController::class, 'index']);

        Route::get('/evidences', [GuruEvidenceController::class, 'index']);
        Route::post('/evidences', [GuruEvidenceController::class, 'store']);
        Route::get('/evidences/{id}', [GuruEvidenceController::class, 'show']);
        Route::post('/evidences/{id}', [GuruEvidenceController::class, 'update']);
        Route::delete('/evidences/{id}', [GuruEvidenceController::class, 'destroy']);

        Route::get('/self-assessments', [GuruSelfAssessmentController::class, 'index']);
        Route::post('/self-assessments', [GuruSelfAssessmentController::class, 'store']);
        Route::get('/self-assessments/{id}', [GuruSelfAssessmentController::class, 'show']);
        Route::put('/self-assessments/{id}', [GuruSelfAssessmentController::class, 'update']);
        Route::post('/self-assessments/{id}/submit', [GuruSelfAssessmentController::class, 'submit']);

        Route::get('/settings', [GuruSettingsController::class, 'index']);
        Route::put('/settings/profile', [GuruSettingsController::class, 'updateProfile']);
        Route::post('/settings/photo', [GuruSettingsController::class, 'uploadPhoto']);
        Route::put('/settings/theme', [GuruSettingsController::class, 'updateTheme']);
        Route::post('/settings/achievements', [GuruSettingsController::class, 'addAchievement']);
        Route::delete('/settings/achievements/{achievementId}', [GuruSettingsController::class, 'deleteAchievement']);
        Route::post('/settings/certifications', [GuruSettingsController::class, 'addCertification']);
        Route::delete('/settings/certifications/{certificationId}', [GuruSettingsController::class, 'deleteCertification']);

        Route::post('/reflections/{evaluationId}', [GuruReflectionController::class, 'store']);
    });
});
