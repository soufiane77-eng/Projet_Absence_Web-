@extends('layouts.admin')
@section('title', 'Détails Réclamation')
@section('page-header', $reclamation->title)
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:200px">Titre</th><td>{{ $reclamation->title }}</td></tr>
            <tr><th>Description</th><td>{{ $reclamation->description }}</td></tr>
            <tr><th>Absence liée</th><td>{{ $reclamation->absence_id ? '#'.$reclamation->absence_id : '—' }}</td></tr>
            <tr><th>Statut</th><td><span class="badge bg-{{ $reclamation->status=='resolved'?'success':($reclamation->status=='rejected'?'danger':($reclamation->status=='in_review'?'info':'warning')) }}">{{ $reclamation->status }}</span></td></tr>
            @if($reclamation->resolution_notes)
            <tr><th>Réponse</th><td>{{ $reclamation->resolution_notes }}</td></tr>
            <tr><th>Traitée par</th><td>{{ $reclamation->resolver?->name ?? '—' }}</td></tr>
            <tr><th>Le</th><td>{{ $reclamation->resolved_at?->format('d/m/Y H:i') ?? '—' }}</td></tr>
            @endif
        </table>
    </div>
</div>
@endsection
