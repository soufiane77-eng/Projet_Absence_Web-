<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::whereIn('role', ['teacher', 'student']);

        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.accounts.index', compact('users'));
    }

    public function create(): View
    {
        $teachers = Teacher::whereNull('user_id')->get()->mapWithKeys(fn($t) => [$t->id => "{$t->first_name} {$t->last_name} ({$t->cin}) - Enseignant"]);
        $students = Student::whereNull('user_id')->get()->mapWithKeys(fn($s) => [$s->id => "{$s->first_name} {$s->last_name} ({$s->cne}) - Étudiant"]);
        return view('admin.accounts.create', compact('teachers', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:teacher,student',
            'teacher_id' => 'required_if:type,teacher|exists:teachers,id',
            'student_id' => 'required_if:type,student|exists:students,id',
            'username' => 'required|string|min:3|max:50|unique:users,username|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'required|string|min:8|max:100',
        ], [
            'teacher_id.required_if' => 'Veuillez sélectionner un enseignant.',
            'teacher_id.exists' => 'L\'enseignant sélectionné n\'existe pas ou a déjà un compte.',
            'student_id.required_if' => 'Veuillez sélectionner un étudiant.',
            'student_id.exists' => 'L\'étudiant sélectionné n\'existe pas ou a déjà un compte.',
            'username.required' => 'Le nom d\'utilisateur est requis.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'username.regex' => 'Le nom d\'utilisateur ne peut contenir que des lettres, chiffres, points, tirets et underscores.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if ($validated['type'] === 'teacher') {
            $teacher = Teacher::findOrFail($validated['teacher_id']);
            if ($teacher->user_id) {
                return back()->with('error', 'Cet enseignant a déjà un compte utilisateur.');
            }

            $user = User::create([
                'name' => "{$teacher->first_name} {$teacher->last_name}",
                'username' => $validated['username'],
                'email' => $teacher->email,
                'password' => Hash::make($validated['password']),
                'plain_password' => $validated['password'],
                'role' => 'teacher',
                'is_active' => true,
            ]);
            $user->assignRole('teacher');
            $teacher->update(['user_id' => $user->id]);

            ActivityLog::log(auth()->id(), "Compte enseignant créé pour {$teacher->first_name} {$teacher->last_name} (username: {$validated['username']})");
        } else {
            $student = Student::findOrFail($validated['student_id']);
            if ($student->user_id) {
                return back()->with('error', 'Cet étudiant a déjà un compte utilisateur.');
            }

            $user = User::create([
                'name' => "{$student->first_name} {$student->last_name}",
                'username' => $validated['username'],
                'email' => $student->email ?? "{$student->cne}@etu.uae.ac.ma",
                'password' => Hash::make($validated['password']),
                'plain_password' => $validated['password'],
                'role' => 'student',
                'is_active' => true,
            ]);
            $user->assignRole('student');
            $student->update(['user_id' => $user->id]);

            ActivityLog::log(auth()->id(), "Compte étudiant créé pour {$student->first_name} {$student->last_name} (username: {$validated['username']})");
        }

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Compte utilisateur créé avec succès.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if (!in_array($user->role, ['teacher', 'student'])) {
            return back()->with('error', 'Action non autorisée pour ce type de compte.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        ActivityLog::log(auth()->id(), "Compte {$status} de {$user->name}");

        return redirect()->route('admin.accounts.index')
            ->with('success', "Compte {$status} avec succès.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        if (!in_array($user->role, ['teacher', 'student'])) {
            return back()->with('error', 'Action non autorisée pour ce type de compte.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|max:100|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'plain_password' => $validated['password'],
            'login_attempts' => 0,
            'locked_until' => null,
        ]);

        ActivityLog::log(auth()->id(), "Mot de passe réinitialisé pour {$user->name}");

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    public function toggleLock(User $user): RedirectResponse
    {
        if (!in_array($user->role, ['teacher', 'student'])) {
            return back()->with('error', 'Action non autorisée pour ce type de compte.');
        }

        if ($user->isLocked()) {
            $user->update(['locked_until' => null, 'login_attempts' => 0]);
            ActivityLog::log(auth()->id(), "Compte débloqué de {$user->name}");
            return redirect()->route('admin.accounts.index')->with('success', 'Compte débloqué avec succès.');
        } else {
            $user->update(['locked_until' => now()->addHours(24)]);
            ActivityLog::log(auth()->id(), "Compte bloqué de {$user->name} pour 24h");
            return redirect()->route('admin.accounts.index')->with('success', 'Compte bloqué pour 24h.');
        }
    }

    public function showResetForm(User $user): View
    {
        if (!in_array($user->role, ['teacher', 'student'])) {
            abort(403);
        }
        return view('admin.accounts.reset-password', compact('user'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if (!in_array($user->role, ['teacher', 'student'])) {
            return back()->with('error', 'Action non autorisée pour ce type de compte.');
        }

        $name = $user->name;

        // Dissociate related teacher/student
        if ($user->role === 'teacher' && $user->teacher) {
            $user->teacher->update(['user_id' => null]);
        } elseif ($user->role === 'student' && $user->student) {
            $user->student->update(['user_id' => null]);
        }

        $user->delete();

        ActivityLog::log(auth()->id(), "Compte supprimé de {$name}");

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}
