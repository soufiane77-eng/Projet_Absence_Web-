<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Absence;
use App\Models\Seance;
use App\Models\Justification;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        if (!$student) {
            return view('dashboard.student')->with('stats', [
                'total_absences' => 0,
                'unjustified_absences' => 0,
                'justified_absences' => 0,
                'pending_justifications' => 0,
            ])->with('recentAbsences', collect())
             ->with('upcomingSeances', collect())
             ->with('error', 'Aucun profil étudiant trouvé.');
        }

        $stats = [
            'total_absences' => Absence::where('student_id', $student->id)->count(),
            'unjustified_absences' => Absence::where('student_id', $student->id)
                ->where('is_justified', false)
                ->where('status', 'absent')
                ->count(),
            'justified_absences' => Absence::where('student_id', $student->id)
                ->where('is_justified', true)
                ->count(),
            'pending_justifications' => Justification::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count(),
        ];

        $recentAbsences = Absence::with('seance.module')
            ->where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $upcomingSeances = Seance::with(['module.semester', 'classe'])
            ->where('class_id', $student->class_id)
            ->where('date', '>=', now()->toDateString())
            ->where('status', 'scheduled')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('dashboard.student', compact('stats', 'recentAbsences', 'upcomingSeances'));
    }
}
