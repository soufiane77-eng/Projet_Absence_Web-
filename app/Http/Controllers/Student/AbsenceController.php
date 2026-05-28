<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsenceController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        if (!$student) {
            return view('student.absences.index')->with('error', 'Aucun profil étudiant trouvé.');
        }

        $absences = Absence::with('seance.module')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(20);

        return view('student.absences.index', compact('absences'));
    }

    public function show(Absence $absence): View
    {
        $student = Auth::user()->student;

        if ($absence->student_id !== $student->id) {
            abort(403);
        }

        $absence->load('seance.module', 'justification');
        return view('student.absences.show', compact('absence'));
    }
}
