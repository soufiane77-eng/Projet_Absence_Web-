<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JustificationController extends Controller
{
    /**
     * Affiche la liste des justifications pour les absences
     * liées aux séances du professeur connecté.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Justification::with('student.classe.filiere', 'absence.seance.module', 'reviewer')
            ->whereHas('absence.seance', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filtre par module si le prof a plusieurs modules
        if ($moduleId = $request->get('module_id')) {
            $query->whereHas('absence', fn($q) => $q->where('module_id', $moduleId));
        }

        $justifications = $query->latest()->paginate(20);

        // Récupérer les modules du professeur pour le filtre
        $modules = $user->modules()->with('classe')->get()
            ->mapWithKeys(fn($m) => [$m->id => "{$m->name} ({$m->classe->name})"]);

        return view('teacher.justifications.index', compact('justifications', 'modules'));
    }

    /**
     * Affiche le détail d'une justification.
     */
    public function show(Justification $justification): View
    {
        $user = Auth::user();

        // Vérifier que la justification concerne une absence
        // liée à une séance du professeur
        if (!$justification->absence || $justification->absence->seance->teacher_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette justification.');
        }

        $justification->load('student.classe.filiere', 'absence.seance.module', 'student.user', 'reviewer');
        return view('teacher.justifications.show', compact('justification'));
    }
}
