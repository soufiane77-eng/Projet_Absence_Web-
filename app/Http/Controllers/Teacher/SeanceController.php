<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Student;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SeanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Seance::with('module', 'classe')
            ->where('teacher_id', $user->id);

        if ($classId = $request->get('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $seances = $query->latest('date')->paginate(15);
        $classes = $user->modules()->withPivot('class_id')->get()->pluck('pivot.class_id')->unique();
        $classes = \App\Models\Classe::whereIn('id', $classes)->pluck('name', 'id');

        return view('teacher.seances.index', compact('seances', 'classes'));
    }

    public function create(): View
    {
        $user = Auth::user();
        $modules = $user->modules()->with('classe')->get();
        $classes = \App\Models\Classe::whereIn('id',
            $modules->pluck('pivot.class_id')->unique()
        )->pluck('name', 'id');

        return view('teacher.seances.create', compact('modules', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'class_id' => 'required|exists:classes,id',
            'type' => 'required|in:cours,td,tp',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $validated['teacher_id'] = Auth::id();
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'scheduled';

        Seance::create($validated);

        return redirect()->route('teacher.seances.index')
            ->with('success', 'Séance créée avec succès.');
    }

    public function markAbsences(Seance $seance): View
    {
        $this->authorizeTeacher($seance);

        $students = Student::where('class_id', $seance->class_id)
            ->where('is_active', true)
            ->get();

        $existingAbsences = Absence::where('seance_id', $seance->id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.seances.mark-absences', compact('seance', 'students', 'existingAbsences'));
    }

    public function storeAbsences(Request $request, Seance $seance): RedirectResponse
    {
        $this->authorizeTeacher($seance);

        $validated = $request->validate([
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,absent,late',
        ]);

        foreach ($validated['students'] as $item) {
            Absence::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'seance_id' => $seance->id,
                ],
                [
                    'module_id' => $seance->module_id,
                    'marked_by' => Auth::id(),
                    'status' => $item['status'],
                ]
            );
        }

        $seance->update(['status' => 'completed']);

        return redirect()->route('teacher.seances.index')
            ->with('success', 'Absences enregistrées avec succès.');
    }

    private function authorizeTeacher(Seance $seance): void
    {
        if ($seance->teacher_id !== Auth::id()) {
            abort(403, 'Cette séance ne vous appartient pas.');
        }
    }
}
