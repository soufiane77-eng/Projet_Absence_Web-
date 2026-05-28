<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReclamationController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        $reclamations = Reclamation::with('absence.seance.module')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        return view('student.reclamations.index', compact('reclamations'));
    }

    public function create(Request $request): View
    {
        $student = Auth::user()->student;
        $absenceId = $request->get('absence_id');

        $absences = Absence::where('student_id', $student->id)
            ->with('seance.module')
            ->get();

        return view('student.reclamations.create', compact('absences', 'absenceId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;

        $validated = $request->validate([
            'absence_id' => 'nullable|exists:absences,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Reclamation::create([
            'student_id' => $student->id,
            'absence_id' => $validated['absence_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        return redirect()->route('student.reclamations.index')
            ->with('success', 'Réclamation soumise avec succès.');
    }

    public function show(Reclamation $reclamation): View
    {
        $student = Auth::user()->student;

        if ($reclamation->student_id !== $student->id) {
            abort(403);
        }

        $reclamation->load('absence.seance.module', 'resolver');
        return view('student.reclamations.show', compact('reclamation'));
    }
}
