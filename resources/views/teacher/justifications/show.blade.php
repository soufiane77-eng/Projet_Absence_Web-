@extends('layouts.admin')
@section('title', 'Détails Justification')
@section('page-header', $justification->title)
@section('page-actions')
    <a href="{{ route('teacher.justifications.index') }}" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Retour
    </a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Informations de la justification</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th class="text-muted" style="width:160px">Étudiant</th>
                        <td>
                            <strong>{{ $justification->student->full_name }}</strong>
                            <br><small class="text-muted">{{ $justification->student->cne ?? '—' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Classe</th>
                        <td>{{ $justification->student->classe?->name ?? '—' }}
                            <small class="text-muted">({{ $justification->student->classe?->filiere?->name ?? '—' }})</small>
                        </td>
                    </tr>
                    @if($justification->absence)
                    <tr>
                        <th class="text-muted">Absence</th>
                        <td>
                            {{ $justification->absence->seance?->module->name ?? '—' }}
                            <br><small class="text-muted">
                                {{ $justification->absence->seance?->date?->format('d/m/Y') ?? '—' }}
                                {{ $justification->absence->seance?->start_time ? substr($justification->absence->seance->start_time,0,5) : '' }}
                                {{ $justification->absence->seance?->end_time ? '-'.substr($justification->absence->seance->end_time,0,5) : '' }}
                            </small>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-muted">Titre</th>
                        <td>{{ $justification->title }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Description</th>
                        <td>{{ $justification->description ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Statut</th>
                        <td>
                            <span class="badge bg-{{ $justification->status=='accepted'?'success':($justification->status=='rejected'?'danger':'warning') }}">
                                {{ $justification->status=='accepted'?'Acceptée':($justification->status=='rejected'?'Rejetée':'En attente') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Soumise le</th>
                        <td>{{ $justification->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($justification->review_notes)
                    <tr>
                        <th class="text-muted">Notes du réviseur</th>
                        <td>{{ $justification->review_notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Document justificatif</h6>
            </div>
            <div class="card-body text-center">
                @if($justification->document_path)
                    @php
                        $ext = strtolower(pathinfo($justification->document_path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                    @endphp
                    @if($isImage)
                        <a href="{{ asset('storage/'.$justification->document_path) }}" target="_blank">
                            <img src="{{ asset('storage/'.$justification->document_path) }}"
                                 alt="Document justificatif"
                                 class="img-fluid rounded border"
                                 style="max-height: 400px; cursor: pointer;">
                        </a>
                        <br><br>
                        <a href="{{ asset('storage/'.$justification->document_path) }}" target="_blank"
                           class="btn btn-outline-primary">
                            <i class="fa fa-external-link"></i> Ouvrir en grand
                        </a>
                    @else
                        <div class="py-4">
                            <i class="fa fa-file-pdf-o" style="font-size: 64px; color: #dc3545;"></i>
                            <p class="mt-2 text-muted">Document PDF</p>
                            <a href="{{ asset('storage/'.$justification->document_path) }}" target="_blank"
                               class="btn btn-outline-primary">
                                <i class="fa fa-download"></i> Télécharger le PDF
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-muted py-4 mb-0">
                        <i class="fa fa-file-o" style="font-size: 48px;"></i><br>
                        Aucun document joint.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
