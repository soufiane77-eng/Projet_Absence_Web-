<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\FiliereController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ElementController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SeanceController;
use App\Http\Controllers\Admin\AbsenceController;
use App\Http\Controllers\Admin\ReclamationController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LoginHistoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'account.status', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Filieres
        Route::resource('filieres', FiliereController::class);

        // Classes
        Route::resource('classes', ClasseController::class);

        // Modules
        Route::resource('modules', ModuleController::class);

        // Elements
        Route::resource('elements', ElementController::class);

        // Semesters
        Route::resource('semesters', SemesterController::class);

        // Teachers
        Route::resource('teachers', TeacherController::class);

        // Students
        Route::resource('students', StudentController::class);

        // Seances
        Route::resource('seances', SeanceController::class);

        // Absences
        Route::resource('absences', AbsenceController::class)->except(['create', 'store']);
        Route::post('absences/batch', [AbsenceController::class, 'batchStore'])->name('absences.batch');
        Route::get('justifications', [AbsenceController::class, 'justificationsIndex'])->name('absences.justifications');
        Route::post('justifications/{justification}/approve', [AbsenceController::class, 'approveJustification'])->name('absences.justifications.approve');
        Route::post('justifications/{justification}/reject', [AbsenceController::class, 'rejectJustification'])->name('absences.justifications.reject');

        // Reclamations
        Route::resource('reclamations', ReclamationController::class)->only(['index', 'show']);
        Route::post('reclamations/{reclamation}/resolve', [ReclamationController::class, 'resolve'])->name('reclamations.resolve');

        // Teacher Assignments
        Route::get('assignments/teachers-list', [TeacherAssignmentController::class, 'teachersJson'])->name('assignments.teachers-json');
        Route::resource('assignments', TeacherAssignmentController::class)->except(['show', 'edit', 'update']);

        // Account Management
        Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('accounts/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::post('accounts/{user}/toggle-active', [AccountController::class, 'toggleActive'])->name('accounts.toggle-active');
        Route::get('accounts/{user}/reset-password', [AccountController::class, 'showResetForm'])->name('accounts.reset-password.form');
        Route::post('accounts/{user}/reset-password', [AccountController::class, 'resetPassword'])->name('accounts.reset-password');
        Route::post('accounts/{user}/toggle-lock', [AccountController::class, 'toggleLock'])->name('accounts.toggle-lock');
        Route::delete('accounts/{user}', [AccountController::class, 'destroy'])->name('accounts.destroy');

        // Profile & Settings
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'showProfile'])->name('profile');
        Route::post('profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::get('settings', [\App\Http\Controllers\ProfileController::class, 'showSettings'])->name('settings');

        // Logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
        Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history');
    });
