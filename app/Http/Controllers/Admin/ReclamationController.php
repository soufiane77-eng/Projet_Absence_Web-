<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReclamationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Reclamation::with('student.classe.filiere', 'absence');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $reclamations = $query->latest()->paginate(20);
        return view('admin.reclamations.index', compact('reclamations'));
    }

    public function show(Reclamation $reclamation): View
    {
        $reclamation->load('student.classe.filiere', 'absence.seance.module', 'resolver');
        return view('admin.reclamations.show', compact('reclamation'));
    }

    public function resolve(Request $request, Reclamation $reclamation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:resolved,rejected',
            'resolution_notes' => 'nullable|string',
        ]);

        $reclamation->update([
            'status' => $validated['status'],
            'resolution_notes' => $validated['resolution_notes'],
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.reclamations.index')
            ->with('success', 'Réclamation mise à jour avec succès.');
    }
}
