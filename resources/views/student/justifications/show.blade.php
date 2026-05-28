@extends('layouts.admin')
@section('title', 'Détails Justification')
@section('page-header', $justification->title)
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:200px">Titre</th><td>{{ $justification->title }}</td></tr>
            <tr><th>Description</th><td>{{ $justification->description ?? '—' }}</td></tr>
            <tr><th>Document</th><td>@if($justification->document_path)<a href="{{ asset('storage/'.$justification->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Télécharger</a>@else — @endif</td></tr>
            <tr><th>Statut</th><td><span class="badge bg-{{ $justification->status=='accepted'?'success':($justification->status=='rejected'?'danger':'warning') }}">{{ $justification->status }}</span></td></tr>
            @if($justification->review_notes)
            <tr><th>Notes du réviseur</th><td>{{ $justification->review_notes }}</td></tr>
            @endif
            <tr><th>Soumise le</th><td>{{ $justification->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
