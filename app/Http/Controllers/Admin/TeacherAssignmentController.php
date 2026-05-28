<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherModule;
use App\Models\User;
use App\Models\Module;
use App\Models\Classe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherAssignmentController extends Controller
{
    public function index(): View
    {
        $assignments = TeacherModule::with('user', 'module.classe.filiere', 'classe')
            ->latest()->paginate(20);
        return view('admin.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $teachers = User::where('role', 'teacher')->get()->mapWithKeys(fn($t) => [$t->id => $t->name]);
        $modules = Module::with('classe.filiere')->get()->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);
        $classes = Classe::with('filiere')->get()->mapWithKeys(fn($c) => [$c->id => "{$c->name} ({$c->filiere->name})"]);
        return view('admin.assignments.create', compact('teachers', 'modules', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
            'class_id' => 'required|exists:classes,id',
            'type' => 'required|in:cours,td,tp',
        ]);

        TeacherModule::create($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Affectation créée avec succès.');
    }

    public function teachersJson(): JsonResponse
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json($teachers);
    }

    public function destroy(TeacherModule $assignment): RedirectResponse
    {
        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Affectation supprimée avec succès.');
    }
}
