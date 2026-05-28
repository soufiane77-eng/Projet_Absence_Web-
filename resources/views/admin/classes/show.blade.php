@extends('layouts.admin')
@section('title', $classe->name)
@section('page-header', $classe->name)
@section('page-actions')
    <a href="{{ route('admin.classes.edit', ['class' => $classe->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
    <a href="{{ route('admin.modules.create') }}?class_id={{ $classe->id }}" class="btn btn-success"><i class="fa fa-plus"></i> Module</a>
    <a href="{{ route('admin.students.create') }}?class_id={{ $classe->id }}" class="btn btn-info"><i class="fa fa-plus"></i> Étudiant</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Détails</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Code</th><td><span class="badge bg-secondary">{{ $classe->code }}</span></td></tr>
                    <tr><th class="text-muted">Filière</th><td><a href="{{ route('admin.filieres.show', $classe->filiere) }}">{{ $classe->filiere->name }}</a></td></tr>
                    <tr><th class="text-muted">Niveau</th><td>{{ $classe->level ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Étudiants</th><td><span class="badge bg-info">{{ $classe->students->count() }}</span></td></tr>
                    <tr><th class="text-muted">Modules</th><td><span class="badge bg-info">{{ $classe->modules->count() }}</span></td></tr>
                    <tr><th class="text-muted">Enseignants</th><td><span class="badge bg-warning text-dark">{{ $classe->teacherModules->unique('user_id')->count() }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <ul class="nav nav-tabs" id="classTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="modules-tab" data-bs-toggle="tab" href="#modules">Modules ({{ $classe->modules->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" id="teachers-tab" data-bs-toggle="tab" href="#teachers">Enseignants ({{ $classe->teacherModules->unique('user_id')->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" id="students-tab" data-bs-toggle="tab" href="#students">Étudiants ({{ $classe->students->count() }})</a></li>
        </ul>
        <div class="tab-content mt-3">
            {{-- Onglet Modules --}}
            <div class="tab-pane fade show active" id="modules">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Code</th><th>Module</th><th>Semestre</th><th>Coeff.</th><th>Éléments</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                @forelse($classe->modules as $module)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $module->code }}</span></td>
                                    <td><a href="{{ route('admin.modules.show', $module) }}">{{ $module->name }}</a></td>
                                    <td>{{ $module->semester?->name ?? '—' }}</td>
                                    <td>{{ $module->coefficient }}</td>
                                    <td>{{ $module->elements->count() }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4">Aucun module.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Onglet Enseignants --}}
            <div class="tab-pane fade" id="teachers">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Enseignant</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Spécialité</th>
                                    <th>Grade</th>
                                    <th>Modules enseignés</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $uniqueTeachers = $classe->teacherModules->groupBy('user_id');
                                @endphp
                                @forelse($uniqueTeachers as $userId => $assignments)
                                    @php
                                        $teacher = $assignments->first()->user;
                                        $teacherProfile = $teacher->teacher;
                                        $moduleNames = $assignments->pluck('module.name')->filter()->implode(', ');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($teacherProfile && $teacherProfile->photo)
                                                    <img src="{{ asset('storage/' . $teacherProfile->photo) }}" alt="" class="rounded-circle me-2" width="32" height="32" style="object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;">
                                                        <i class="fa fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $teacher->name }}</strong>
                                                    @if($teacherProfile)
                                                        <br><small class="text-muted">{{ $teacherProfile->cin ?? '' }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $teacher->email ?? '—' }}</td>
                                        <td>{{ $teacherProfile?->phone ?? $teacher->phone ?? '—' }}</td>
                                        <td>{{ $teacherProfile?->specialty ?? '—' }}</td>
                                        <td>{{ $teacherProfile?->grade ?? '—' }}</td>
                                        <td><small>{{ $moduleNames ?: '—' }}</small></td>
                                        <td class="text-center">
                                            @if($teacher->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-4">Aucun enseignant assigné à cette classe.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Onglet Étudiants --}}
            <div class="tab-pane fade" id="students">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Code Massar</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>CIN</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Genre</th>
                                    <th>Semestre</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classe->students as $student)
                                <tr>
                                    <td><code>{{ $student->cne }}</code></td>
                                    <td>{{ $student->last_name }}</td>
                                    <td>{{ $student->first_name }}</td>
                                    <td>{{ $student->cin ?? '—' }}</td>
                                    <td><a href="mailto:{{ $student->email }}">{{ $student->email ?? '—' }}</a></td>
                                    <td>{{ $student->phone ?? '—' }}</td>
                                    <td>
                                        @if($student->gender === 'male')
                                            <span class="badge bg-info">Masculin</span>
                                        @elseif($student->gender === 'female')
                                            <span class="badge bg-warning text-dark">Féminin</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $student->currentSemester?->name ?? '—' }}</td>
                                    <td class="text-center">
                                        @if($student->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="11" class="text-center py-4">Aucun étudiant.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
