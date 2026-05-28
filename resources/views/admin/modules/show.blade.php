@extends('layouts.admin')
@section('title', $module->name)
@section('page-header', $module->name)
@section('page-actions')
    <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
    <a href="{{ route('admin.elements.create') }}?module_id={{ $module->id }}" class="btn btn-success"><i class="fa fa-plus"></i> Élément</a>
    @endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Détails</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Code</th><td><span class="badge bg-secondary">{{ $module->code }}</span></td></tr>
                    <tr><th class="text-muted">Classe</th><td><a href="{{ route('admin.modules.index') }}">{{ $module->classe->name }}</a></td></tr>
                    <tr><th class="text-muted">Filière</th><td><a href="{{ route('admin.filieres.show', $module->classe->filiere) }}">{{ $module->classe->filiere->name }}</a></td></tr>
                    <tr><th class="text-muted">Semestre</th><td>{{ $module->semester?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Coefficient</th><td>{{ $module->coefficient }}</td></tr>
                    <tr><th class="text-muted">Heures</th><td>{{ $module->total_hours }}</td></tr>
                </table>
                @if($module->description)<hr><p class="text-muted">{{ $module->description }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#elements">Éléments ({{ $module->elements->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#seances">Séances ({{ $module->seances->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#teachers">Enseignants</a></li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="elements">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Code</th><th>Nom</th><th>Coeff.</th><th>Heures</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                @forelse($module->elements as $element)
                                <tr><td><span class="badge bg-secondary">{{ $element->code }}</span></td><td>{{ $element->name }}</td><td>{{ $element->coefficient }}</td><td>{{ $element->total_hours }}</td><td class="text-end">
                                    <a href="{{ route('admin.elements.edit', $element) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                                    <form action="{{ route('admin.elements.destroy', $element) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet élément ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td></tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4">Aucun élément.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="seances">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light"><tr><th>Date</th><th>Heure</th><th>Type</th><th>Salle</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
                            <tbody>
                                @forelse($module->seances as $seance)
                                <tr><td>{{ $seance->date->format('d/m/Y') }}</td><td>{{ substr($seance->start_time,0,5) }}-{{ substr($seance->end_time,0,5) }}</td><td><span class="badge bg-secondary">{{ $seance->type }}</span></td><td>{{ $seance->room ?? '—' }}</td><td>{!! $seance->status=='completed' ? '<span class="badge bg-success">Terminée</span>' : ($seance->status=='cancelled' ? '<span class="badge bg-danger">Annulée</span>' : '<span class="badge bg-warning">Planifiée</span>') !!}</td><td class="text-end"><a href="{{ route('admin.seances.show', $seance) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a></td></tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4">Aucune séance.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="teachers">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @forelse($module->teachers as $teacher)
                        <span class="badge bg-info me-2">{{ $teacher->name }} ({{ $teacher->pivot->type }})</span>
                        @empty
                        <p class="text-muted mb-0">Aucun enseignant assigné.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
