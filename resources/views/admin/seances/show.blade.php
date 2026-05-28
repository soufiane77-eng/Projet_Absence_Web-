@extends('layouts.admin')
@section('title', 'Détails Séance')
@section('page-header', 'Séance du '.$seance->date->format('d/m/Y'))
@section('page-actions')
    <a href="{{ route('admin.seances.edit', $seance) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
    <a href="{{ route('admin.absences.create') }}?seance_id={{ $seance->id }}" class="btn btn-info"><i class="fa fa-plus"></i> Marquer absences</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Date</th><td>{{ $seance->date->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Horaire</th><td>{{ substr($seance->start_time,0,5) }} - {{ substr($seance->end_time,0,5) }}</td></tr>
                    <tr><th class="text-muted">Module</th><td><a href="{{ route('admin.modules.show', $seance->module) }}">{{ $seance->module->name }}</a></td></tr>
                    <tr><th class="text-muted">Classe</th><td><a href="{{ route('admin.modules.index') }}">{{ $seance->classe->name }}</a></td></tr>
                    <tr><th class="text-muted">Type</th><td><span class="badge bg-secondary">{{ $seance->type }}</span></td></tr>
                    <tr><th class="text-muted">Enseignant</th><td>{{ $seance->teacher?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Salle</th><td>{{ $seance->room ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Statut</th><td>{!! $seance->status=='completed'?'<span class="badge bg-success">Terminée</span>':($seance->status=='cancelled'?'<span class="badge bg-danger">Annulée</span>':'<span class="badge bg-warning">Planifiée</span>') !!}</td></tr>
                </table>
                @if($seance->notes)<hr><p class="text-muted">{{ $seance->notes }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Absences ({{ $seance->absences->count() }})</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Étudiant</th><th>Statut</th><th>Justifié</th><th>Notes</th></tr></thead>
                    <tbody>
                        @forelse($seance->absences as $absence)
                        <tr>
                            <td><a href="{{ route('admin.students.show', $absence->student) }}">{{ $absence->student->full_name }}</a></td>
                            <td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td>
                            <td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td>
                            <td>{{ $absence->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Aucune absence enregistrée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
