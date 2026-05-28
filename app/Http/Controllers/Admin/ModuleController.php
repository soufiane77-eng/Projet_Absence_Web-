<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Classe;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Module::with('classe.filiere', 'semester', 'teachers');

        if ($semesterId = $request->get('semester_id')) {
            $query->where('semester_id', $semesterId);
        }

        if ($classId = $request->get('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $modules = $query->latest()->paginate(15);
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });
        $semesters = Semester::pluck('name', 'id');

        return view('admin.modules.index', compact('modules', 'classes', 'semesters'));
    }

    public function create(): View
    {
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });
        $semesters = Semester::pluck('name', 'id');
        return view('admin.modules.create', compact('classes', 'semesters'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:modules,code',
            'class_id' => 'required|exists:classes,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'coefficient' => 'required|integer|min:1|max:10',
            'total_hours' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Module::create($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module créé avec succès.');
    }

    public function show(Module $module): View
    {
        $module->load('classe.filiere', 'semester', 'elements', 'seances', 'teachers');
        return view('admin.modules.show', compact('module'));
    }

    public function edit(Module $module): View
    {
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });
        $semesters = Semester::pluck('name', 'id');
        return view('admin.modules.edit', compact('module', 'classes', 'semesters'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:modules,code,' . $module->id,
            'class_id' => 'required|exists:classes,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'coefficient' => 'required|integer|min:1|max:10',
            'total_hours' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $module->update($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module mis à jour avec succès.');
    }

    public function destroy(Module $module): RedirectResponse
    {
        if ($module->elements()->exists()) {
            return redirect()->route('admin.modules.index')
                ->with('error', 'Impossible de supprimer ce module car il contient des éléments.');
        }

        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module supprimé avec succès.');
    }
}
