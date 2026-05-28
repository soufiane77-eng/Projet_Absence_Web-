@extends('layouts.admin')
@section('title', 'Détails Absence')
@section('page-header', 'Détails de l\'absence')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-sm">
            <tr><th style="width:200px">Date</th><td>{{ $absence->seance->date->format('d/m/Y') }}</td></tr>
            <tr><th>Horaire</th><td>{{ substr($absence->seance->start_time,0,5) }} - {{ substr($absence->seance->end_time,0,5) }}</td></tr>
            <tr><th>Module</th><td>{{ $absence->seance->module->name }}</td></tr>
            <tr><th>Type</th><td><span class="badge bg-secondary">{{ $absence->seance->type }}</span></td></tr>
            <tr><th>Statut</th><td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td></tr>
            <tr><th>Justifié</th><td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td></tr>
        </table>
        @if(!$absence->is_justified && $absence->status!='present')
        <div class="mt-3">
            <a href="{{ route('student.justifications.create', ['absence_id'=>$absence->id]) }}" class="btn btn-primary"><i class="fa fa-file"></i> Soumettre une justification</a>
            <a href="{{ route('student.reclamations.create', ['absence_id'=>$absence->id]) }}" class="btn btn-warning"><i class="fa fa-exclamation"></i> Faire une réclamation</a>
        </div>
        @endif
    </div>
</div>
@endsection
