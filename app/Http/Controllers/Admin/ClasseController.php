<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Filiere;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClasseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Classe::with(['filiere', 'teacherModules.user']);

        if ($filiereId = $request->get('filiere_id')) {
            $query->where('filiere_id', $filiereId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('students', function ($sq) use ($search) {
                      $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $classes = $query->latest()->paginate(15);
        $filieres = Filiere::pluck('name', 'id');

        return view('admin.classes.index', compact('classes', 'filieres'));
    }

    public function create(): View
    {
        $filieres = Filiere::pluck('name', 'id');
        return view('admin.classes.create', compact('filieres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes,code',
            'filiere_id' => 'required|exists:filieres,id',
            'level' => 'nullable|string|max:50',
        ]);

        Classe::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function show(Classe $classe): View
    {
        $classe->load([
            'filiere',
            'students.currentSemester',
            'modules.elements',
            'modules.semester',
            'teacherModules.user',
            'teacherModules.module',
        ]);

        return view('admin.classes.show', compact('classe'));
    }

    public function edit(Classe $classe): View
    {
        $filieres = Filiere::pluck('name', 'id');
        return view('admin.classes.edit', compact('classe', 'filieres'));
    }

    public function update(Request $request, Classe $classe): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:classes,code,' . $classe->id,
            'filiere_id' => 'required|exists:filieres,id',
            'level' => 'nullable|string|max:50',
        ]);

        $classe->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe): RedirectResponse
    {
        if ($classe->students()->exists()) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Impossible de supprimer cette classe car elle contient des étudiants.');
        }

        $classe->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe supprimée avec succès.');
    }
}
