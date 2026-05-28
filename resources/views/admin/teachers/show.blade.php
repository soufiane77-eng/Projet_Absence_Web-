@extends('layouts.admin')
@section('title', 'Détails Enseignant')
@section('page-header', $teacher->full_name)
@section('page-actions')
    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                @if($teacher->photo)
                <img src="{{ asset('storage/'.$teacher->photo) }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover">
                @else
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;font-size:2.5rem">{{ substr($teacher->first_name,0,1) }}{{ substr($teacher->last_name,0,1) }}</div>
                @endif
                <h5 class="fw-bold">{{ $teacher->full_name }}</h5>
                <p class="text-muted small">{{ $teacher->specialty ?? '—' }}</p>
                <span class="badge bg-info">{{ $teacher->grade ?? '—' }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Informations</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th style="width:200px">CIN</th><td><code>{{ $teacher->cin }}</code></td></tr>
                    <tr><th>Nom</th><td>{{ $teacher->last_name }} {{ $teacher->first_name }}</td></tr>
                    <tr><th>Nom (Arabe)</th><td>{{ $teacher->last_name_ar ?? '—' }} {{ $teacher->first_name_ar ?? '—' }}</td></tr>
                    <tr><th>Email</th><td>{{ $teacher->email }}</td></tr>
                    <tr><th>Téléphone</th><td>{{ $teacher->phone ?? '—' }}</td></tr>
                    <tr><th>Adresse</th><td>{{ $teacher->address ?? '—' }}</td></tr>
                    <tr><th>Spécialité</th><td>{{ $teacher->specialty ?? '—' }}</td></tr>
                    <tr><th>Grade</th><td>{{ $teacher->grade ?? '—' }}</td></tr>
                    <tr><th>Compte utilisateur</th><td>{{ $teacher->user ? '<span class="badge bg-success">Créé</span>' : '<span class="badge bg-warning">Non créé</span>' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
