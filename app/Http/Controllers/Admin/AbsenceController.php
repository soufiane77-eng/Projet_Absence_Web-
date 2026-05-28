<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Justification;
use App\Models\Student;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Classe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsenceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Absence::with('student.classe.filiere', 'seance.module', 'module');

        if ($semesterId = $request->get('semester_id')) {
            $query->whereHas('module', fn($q) => $q->where('semester_id', $semesterId));
        }

        if ($classId = $request->get('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $classId));
        }

        if ($moduleId = $request->get('module_id')) {
            $query->where('module_id', $moduleId);
        }

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
        $classes = Classe::with('filiere')->get()->mapWithKeys(fn($c) => [$c->id => "{$c->name} ({$c->filiere->name})"]);
        $modules = Module::with('classe')->get()->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);
        $semesters = \App\Models\Semester::pluck('name', 'id');

        return view('admin.absences.index', compact('absences', 'classes', 'modules', 'semesters'));
    }

    public function show(Absence $absence): View
    {
        $absence->load('student.classe.filiere', 'seance.module', 'module', 'marker', 'justification');
        return view('admin.absences.show', compact('absence'));
    }

    public function edit(Absence $absence): View
    {
        $absence->load('student', 'seance');
        return view('admin.absences.edit', compact('absence'));
    }

    public function update(Request $request, Absence $absence): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,justified,late',
            'notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'justified') {
            $validated['is_justified'] = true;
        }

        $absence->update($validated);

        return redirect()->route('admin.absences.index')
            ->with('success', 'Absence mise à jour avec succès.');
    }

    public function batchStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'students' => 'required|array',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.status' => 'required|in:present,absent,late',
        ]);

        $seance = Seance::findOrFail($validated['seance_id']);

        foreach ($validated['students'] as $item) {
            Absence::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'seance_id' => $seance->id,
                ],
                [
                    'module_id' => $seance->module_id,
                    'marked_by' => auth()->id(),
                    'status' => $item['status'],
                ]
            );
        }

        return redirect()->route('admin.seances.show', $seance)
            ->with('success', 'Absences enregistrées avec succès.');
    }

    public function justificationsIndex(Request $request): View
    {
        $query = Justification::with('student.classe.filiere', 'absence.seance.module', 'reviewer');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $justifications = $query->latest()->paginate(20);
        return view('admin.justifications.index', compact('justifications'));
    }

    public function approveJustification(Request $request, Justification $justification): RedirectResponse
    {
        $justification->update([
            'status' => 'accepted',
            'reviewed_by' => auth()->id(),
            'review_notes' => $request->input('review_notes'),
            'reviewed_at' => now(),
        ]);

        if ($justification->absence) {
            $justification->absence->update([
                'is_justified' => true,
                'status' => 'justified',
            ]);
        }

        return redirect()->route('admin.absences.justifications')
            ->with('success', 'Justification approuvée.');
    }

    public function rejectJustification(Request $request, Justification $justification): RedirectResponse
    {
        $justification->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'review_notes' => $request->input('review_notes'),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.absences.justifications')
            ->with('success', 'Justification rejetée.');
    }
}
