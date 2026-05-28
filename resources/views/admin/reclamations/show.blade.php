@extends('layouts.admin')
@section('title', 'Détails Réclamation')
@section('page-header', $reclamation->title)
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Étudiant</th><td><a href="{{ route('admin.students.show', $reclamation->student) }}">{{ $reclamation->student->full_name }}</a></td></tr>
                    <tr><th class="text-muted">Titre</th><td>{{ $reclamation->title }}</td></tr>
                    <tr><th class="text-muted">Description</th><td>{{ $reclamation->description }}</td></tr>
                    <tr><th class="text-muted">Absence liée</th><td>{{ $reclamation->absence_id ? '#' . $reclamation->absence_id : '—' }}</td></tr>
                    <tr><th class="text-muted">Statut</th><td><span class="badge bg-{{ $reclamation->status=='resolved'?'success':($reclamation->status=='rejected'?'danger':($reclamation->status=='in_review'?'info':'warning')) }}">{{ $reclamation->status }}</span></td></tr>
                    <tr><th class="text-muted">Date</th><td>{{ $reclamation->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Traitement</h6></div>
            <div class="card-body">
                @if($reclamation->status=='open')
                <form action="{{ route('admin.reclamations.resolve', $reclamation) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select name="status" class="form-select" required>
                            <option value="resolved">Résoudre</option>
                            <option value="rejected">Rejeter</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Notes de résolution</label><textarea name="resolution_notes" rows="3" class="form-control"></textarea></div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Soumettre</button>
                </form>
                @else
                <table class="table table-sm">
                    <tr><th class="text-muted">Résolue par</th><td>{{ $reclamation->resolver?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Résolue le</th><td>{{ $reclamation->resolved_at?->format('d/m/Y H:i') ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Notes</th><td>{{ $reclamation->resolution_notes ?? '—' }}</td></tr>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
