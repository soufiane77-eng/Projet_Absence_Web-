<?php

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\SeanceController as TeacherSeanceController;
use App\Http\Controllers\Teacher\AbsenceController as TeacherAbsenceController;
use App\Http\Controllers\Teacher\JustificationController as TeacherJustificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'account.status', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

        // Seances
        Route::get('seances', [TeacherSeanceController::class, 'index'])->name('seances.index');
        Route::get('seances/create', [TeacherSeanceController::class, 'create'])->name('seances.create');
        Route::post('seances', [TeacherSeanceController::class, 'store'])->name('seances.store');
        Route::get('seances/{seance}/mark-absences', [TeacherSeanceController::class, 'markAbsences'])->name('seances.mark-absences');
        Route::post('seances/{seance}/store-absences', [TeacherSeanceController::class, 'storeAbsences'])->name('seances.store-absences');

        // Absences
        Route::get('absences', [TeacherAbsenceController::class, 'index'])->name('absences.index');
        Route::get('absences/{absence}', [TeacherAbsenceController::class, 'show'])->name('absences.show');

        // Justifications
        Route::get('justifications', [TeacherJustificationController::class, 'index'])->name('justifications.index');
        Route::get('justifications/{justification}', [TeacherJustificationController::class, 'show'])->name('justifications.show');
    });
