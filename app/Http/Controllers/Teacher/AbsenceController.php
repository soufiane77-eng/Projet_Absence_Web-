<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsenceController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Absence::with('student.classe.filiere', 'seance.module')
            ->whereHas('seance', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereHas('seance', fn($q) => $q->whereDate('date', '>=', $dateFrom));
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereHas('seance', fn($q) => $q->whereDate('date', '<=', $dateTo));
        }

        $absences = $query->latest()->paginate(20);
        return view('teacher.absences.index', compact('absences'));
    }

    public function show(Absence $absence): View
    {
        $this->authorizeTeacherAbsence($absence);
        $absence->load('student', 'seance.module', 'justification');
        return view('teacher.absences.show', compact('absence'));
    }

    private function authorizeTeacherAbsence(Absence $absence): void
    {
        if ($absence->seance->teacher_id !== Auth::id()) {
            abort(403);
        }
    }
}
