<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Seance::with('module.classe.filiere', 'teacher', 'classe');

        if ($semesterId = $request->get('semester_id')) {
            $query->whereHas('module', fn($q) => $q->where('semester_id', $semesterId));
        }

        if ($moduleId = $request->get('module_id')) {
            $query->where('module_id', $moduleId);
        }

        if ($classId = $request->get('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $seances = $query->latest('date')->latest('start_time')->paginate(15);
        $modules = Module::with('classe')->get()->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);
        $classes = Classe::with('filiere')->get()->mapWithKeys(fn($c) => [$c->id => "{$c->name} ({$c->filiere->name})"]);
        $semesters = \App\Models\Semester::pluck('name', 'id');

        return view('admin.seances.index', compact('seances', 'modules', 'classes', 'semesters'));
    }

    public function create(): View
    {
        $modules = Module::with('classe')->get()->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);
        $teachers = User::where('role', 'teacher')->get()->mapWithKeys(fn($t) => [$t->id => $t->name]);
        $classes = Classe::with('filiere')->get()->mapWithKeys(fn($c) => [$c->id => "{$c->name} ({$c->filiere->name})"]);
        return view('admin.seances.create', compact('modules', 'teachers', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'teacher_id' => 'nullable|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'type' => 'required|in:cours,td,tp',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $validated['created_by'] = auth()->id();
        Seance::create($validated);

        return redirect()->route('admin.seances.index')
            ->with('success', 'Séance créée avec succès.');
    }

    public function show(Seance $seance): View
    {
        $seance->load('module.classe.filiere', 'teacher', 'classe', 'absences.student', 'creator');
        return view('admin.seances.show', compact('seance'));
    }

    public function edit(Seance $seance): View
    {
        $modules = Module::with('classe')->get()->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);
        $teachers = User::where('role', 'teacher')->get()->mapWithKeys(fn($t) => [$t->id => $t->name]);
        $classes = Classe::with('filiere')->get()->mapWithKeys(fn($c) => [$c->id => "{$c->name} ({$c->filiere->name})"]);
        return view('admin.seances.edit', compact('seance', 'modules', 'teachers', 'classes'));
    }

    public function update(Request $request, Seance $seance): RedirectResponse
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'teacher_id' => 'nullable|exists:users,id',
            'class_id' => 'required|exists:classes,id',
            'type' => 'required|in:cours,td,tp',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $seance->update($validated);

        return redirect()->route('admin.seances.index')
            ->with('success', 'Séance mise à jour avec succès.');
    }

    public function destroy(Seance $seance): RedirectResponse
    {
        $seance->delete();

        return redirect()->route('admin.seances.index')
            ->with('success', 'Séance supprimée avec succès.');
    }
}
