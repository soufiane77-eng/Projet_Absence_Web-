<?php

use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\AbsenceController as StudentAbsenceController;
use App\Http\Controllers\Student\JustificationController as StudentJustificationController;
use App\Http\Controllers\Student\ReclamationController as StudentReclamationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'account.status', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        // Absences
        Route::get('absences', [StudentAbsenceController::class, 'index'])->name('absences.index');
        Route::get('absences/{absence}', [StudentAbsenceController::class, 'show'])->name('absences.show');

        // Justifications
        Route::get('justifications', [StudentJustificationController::class, 'index'])->name('justifications.index');
        Route::get('justifications/create', [StudentJustificationController::class, 'create'])->name('justifications.create');
        Route::post('justifications', [StudentJustificationController::class, 'store'])->name('justifications.store');
        Route::get('justifications/{justification}', [StudentJustificationController::class, 'show'])->name('justifications.show');

        // Reclamations
        Route::get('reclamations', [StudentReclamationController::class, 'index'])->name('reclamations.index');
        Route::get('reclamations/create', [StudentReclamationController::class, 'create'])->name('reclamations.create');
        Route::post('reclamations', [StudentReclamationController::class, 'store'])->name('reclamations.store');
        Route::get('reclamations/{reclamation}', [StudentReclamationController::class, 'show'])->name('reclamations.show');
    });
