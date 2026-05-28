<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Element;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ElementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Element::with('module.classe.filiere');

        if ($moduleId = $request->get('module_id')) {
            $query->where('module_id', $moduleId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $elements = $query->latest()->paginate(15);
        $modules = Module::with('classe.filiere')->get()->mapWithKeys(function ($m) {
            return [$m->id => "{$m->name} ({$m->classe->name})"];
        });

        return view('admin.elements.index', compact('elements', 'modules'));
    }

    public function create(): View
    {
        $modules = Module::with('classe.filiere')->get()->mapWithKeys(function ($m) {
            return [$m->id => "{$m->name} ({$m->classe->name})"];
        });
        return view('admin.elements.create', compact('modules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:elements,code',
            'module_id' => 'required|exists:modules,id',
            'coefficient' => 'required|integer|min:1|max:10',
            'total_hours' => 'required|integer|min:1',
        ]);

        Element::create($validated);

        return redirect()->route('admin.elements.index')
            ->with('success', 'Élément créé avec succès.');
    }

    public function show(Element $element): View
    {
        $element->load('module.classe.filiere');
        return view('admin.elements.show', compact('element'));
    }

    public function edit(Element $element): View
    {
        $modules = Module::with('classe.filiere')->get()->mapWithKeys(function ($m) {
            return [$m->id => "{$m->name} ({$m->classe->name})"];
        });
        return view('admin.elements.edit', compact('element', 'modules'));
    }

    public function update(Request $request, Element $element): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:elements,code,' . $element->id,
            'module_id' => 'required|exists:modules,id',
            'coefficient' => 'required|integer|min:1|max:10',
            'total_hours' => 'required|integer|min:1',
        ]);

        $element->update($validated);

        return redirect()->route('admin.elements.index')
            ->with('success', 'Élément mis à jour avec succès.');
    }

    public function destroy(Element $element): RedirectResponse
    {
        $element->delete();

        return redirect()->back()
            ->with('success', 'Élément supprimé avec succès.');
    }
}
