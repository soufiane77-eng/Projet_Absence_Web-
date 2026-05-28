<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Module;
use App\Models\Seance;
use App\Models\Absence;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Justification;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $semesterId = $request->get('semester_id');
        $semester = $semesterId ? Semester::find($semesterId) : null;

        $absencesQuery = Absence::query();
        $seancesQuery = Seance::query();
        $modulesQuery = Module::query();

        if ($semesterId) {
            $modulesQuery->where('semester_id', $semesterId);
            $seancesQuery->whereHas('module', fn($q) => $q->where('semester_id', $semesterId));
            $absencesQuery->whereHas('module', fn($q) => $q->where('semester_id', $semesterId));
        }

        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teacher_profiles' => Teacher::count(),
            'total_student_profiles' => Student::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_filieres' => Filiere::count(),
            'total_classes' => Classe::count(),
            'total_modules' => $modulesQuery->count(),
            'total_seances' => $seancesQuery->count(),
            'total_absences' => $absencesQuery->count(),
            'unjustified_absences' => (clone $absencesQuery)->where('is_justified', false)->where('status', 'absent')->count(),
            'pending_justifications' => Justification::where('status', 'pending')->count(),
            'selected_semester' => $semester,
            'recent_logs' => ActivityLog::with('user')
                ->latest()
                ->take(10)
                ->get(),
        ];

        $semesters = Semester::pluck('name', 'id');

        return view('dashboard.admin', compact('stats', 'semesters'));
    }
}
