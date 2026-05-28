@extends('layouts.admin')
@section('title', 'Détails Absence')
@section('page-header', 'Détails de l\'absence')
@section('page-actions')
    <a href="{{ route('teacher.absences.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Retour</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Étudiant</th><td><a href="{{ route('admin.students.show', $absence->student) }}">{{ $absence->student->full_name }}</a></td></tr>
                    <tr><th class="text-muted">Classe</th><td>{{ $absence->student->classe?->name ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Date</th><td>{{ $absence->seance->date->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Horaire</th><td>{{ substr($absence->seance->start_time,0,5) }}-{{ substr($absence->seance->end_time,0,5) }}</td></tr>
                    <tr><th class="text-muted">Module</th><td>{{ $absence->seance->module->name }}</td></tr>
                    <tr><th class="text-muted">Statut</th><td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td></tr>
                    <tr><th class="text-muted">Justifié</th><td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td></tr>
                </table>
                @if($absence->notes)<hr><p class="text-muted">{{ $absence->notes }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Justification</h6></div>
            <div class="card-body">
                @if($absence->justification)
                <table class="table table-sm">
                    <tr><th class="text-muted">Titre</th><td>{{ $absence->justification->title }}</td></tr>
                    <tr><th class="text-muted">Description</th><td>{{ $absence->justification->description ?? '—' }}</td></tr>
                    <tr><th class="text-muted">Document</th>
                        <td>
                            @if($absence->justification->document_path)
                                @php $ext = strtolower(pathinfo($absence->justification->document_path, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext, ['jpg','jpeg','png']))
                                    <a href="{{ asset('storage/'.$absence->justification->document_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$absence->justification->document_path) }}"
                                             alt="Document" class="img-fluid rounded border" style="max-height: 150px;">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/'.$absence->justification->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-file-pdf-o"></i> Voir le PDF
                                    </a>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">Statut</th>
                        <td>
                            <span class="badge bg-{{ $absence->justification->status=='accepted'?'success':($absence->justification->status=='rejected'?'danger':'warning') }}">
                                {{ $absence->justification->status=='accepted'?'Acceptée':($absence->justification->status=='rejected'?'Rejetée':'En attente') }}
                            </span>
                        </td>
                    </tr>
                    @if($absence->justification->review_notes)
                    <tr><th class="text-muted">Notes</th><td>{{ $absence->justification->review_notes }}</td></tr>
                    @endif
                </table>
                @else
                <p class="text-muted mb-0">Aucune justification soumise.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
