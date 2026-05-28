<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classe;
use App\Models\Absence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::with('classe.filiere');

        if ($classId = $request->get('class_id')) {
            $query->where('class_id', $classId);
        }

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        $students = $query->latest()->paginate(15);
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create(): View
    {
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cne' => 'required|string|max:20|unique:students,cne',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name_ar' => 'nullable|string|max:255',
            'last_name_ar' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'cin' => 'nullable|string|max:20',
            'cin' => 'required|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('students', 'public');
        }

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(Student $student): View
    {
        $student->load('classe.filiere', 'absences.seance.module', 'justifications', 'reclamations');
        $absences = $student->absences()->with('seance.module')->latest()->paginate(20);
        return view('admin.students.show', compact('student', 'absences'));
    }

    public function edit(Student $student): View
    {
        $classes = Classe::with('filiere')->get()->mapWithKeys(function ($c) {
            return [$c->id => "{$c->name} ({$c->filiere->name})"];
        });
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'cne' => 'required|string|max:20|unique:students,cne,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name_ar' => 'nullable|string|max:255',
            'last_name_ar' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'cin' => 'nullable|string|max:20',
            'cin' => 'required|string|max:20',
            'class_id' => 'nullable|exists:classes,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')
                ->store('students', 'public');
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }
}
