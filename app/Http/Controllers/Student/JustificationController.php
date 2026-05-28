<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JustificationController extends Controller
{
    public function index(): View
    {
        $student = Auth::user()->student;

        $justifications = Justification::with('absence.seance.module')
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        return view('student.justifications.index', compact('justifications'));
    }

    public function create(Request $request): View
    {
        $student = Auth::user()->student;
        $absenceId = $request->get('absence_id');

        $unjustifiedAbsences = Absence::where('student_id', $student->id)
            ->where('is_justified', false)
            ->where('status', 'absent')
            ->with('seance.module')
            ->get();

        return view('student.justifications.create', compact('unjustifiedAbsences', 'absenceId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $student = Auth::user()->student;

        $validated = $request->validate([
            'absence_id' => 'nullable|exists:absences,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $data = [
            'student_id' => $student->id,
            'absence_id' => $validated['absence_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ];

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $data['document_path'] = $file->store('justifications', 'public');
            $data['document_type'] = $file->getClientOriginalExtension();
        }

        Justification::create($data);

        return redirect()->route('student.justifications.index')
            ->with('success', 'Justification soumise avec succès.');
    }

    public function show(Justification $justification): View
    {
        $student = Auth::user()->student;

        if ($justification->student_id !== $student->id) {
            abort(403);
        }

        $justification->load('absence.seance.module', 'reviewer');
        return view('student.justifications.show', compact('justification'));
    }
}
