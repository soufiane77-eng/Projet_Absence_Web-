<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FiliereController extends Controller
{
    public function index(Request $request): View
    {
        $query = Filiere::with('coordinator', 'classes');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $filieres = $query->latest()->paginate(15);
        return view('admin.filieres.index', compact('filieres'));
    }

    public function create(): View
    {
        return view('admin.filieres.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:filieres,code',
            'description' => 'nullable|string',
            'coordinator_id' => 'nullable|exists:users,id',
        ]);

        Filiere::create($validated);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès.');
    }

    public function show(Filiere $filiere): View
    {
        $filiere->load('coordinator', 'classes.modules');
        return view('admin.filieres.show', compact('filiere'));
    }

    public function edit(Filiere $filiere): View
    {
        return view('admin.filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:filieres,code,' . $filiere->id,
            'description' => 'nullable|string',
            'coordinator_id' => 'nullable|exists:users,id',
        ]);

        $filiere->update($validated);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière mise à jour avec succès.');
    }

    public function destroy(Filiere $filiere): RedirectResponse
    {
        if ($filiere->classes()->exists()) {
            return redirect()->route('admin.filieres.index')
                ->with('error', 'Impossible de supprimer cette filière car elle contient des classes.');
        }

        $filiere->delete();

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }
}
