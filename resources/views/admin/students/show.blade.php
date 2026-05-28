@extends('layouts.admin')
@section('title', 'Détails Étudiant')
@section('page-header', $student->full_name)
@section('page-actions')
    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
@endsection
@section('content')
<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#info">Informations</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#absences">Absences ({{ $student->absences->count() }})</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#justifications">Justifications ({{ $student->justifications->count() }})</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reclamations">Réclamations ({{ $student->reclamations->count() }})</a></li>
</ul>
<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="info">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        @if($student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover">
                        @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;font-size:2.5rem">{{ substr($student->first_name,0,1) }}{{ substr($student->last_name,0,1) }}</div>
                        @endif
                        <h5 class="fw-bold">{{ $student->full_name }}</h5>
                        <p class="text-muted small">{{ $student->cne }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th style="width:200px">Code Massar</th><td><code>{{ $student->cne }}</code></td></tr>
                            <tr><th>CIN</th><td>{{ $student->cin ?? '—' }}</td></tr>
                            <tr><th>Classe</th><td>{{ $student->classe?->name ?? '—' }}</td></tr>
                            <tr><th>Filière</th><td>{{ $student->classe?->filiere?->name ?? '—' }}</td></tr>
                            <tr><th>Email</th><td>{{ $student->email ?? '—' }}</td></tr>
                            <tr><th>Téléphone</th><td>{{ $student->phone ?? '—' }}</td></tr>
                            <tr><th>Date naissance</th><td>{{ $student->birth_date?->format('d/m/Y') ?? '—' }}</td></tr>
                            <tr><th>Genre</th><td>{{ $student->gender == 'male' ? 'Masculin' : ($student->gender == 'female' ? 'Féminin' : '—') }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="absences">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Date</th><th>Séance</th><th>Module</th><th>Statut</th><th>Justifié</th></tr></thead>
                    <tbody>
                        @forelse($absences as $absence)
                        <tr><td>{{ $absence->seance?->date?->format('d/m/Y') ?? '—' }}</td><td>{{ substr($absence->seance->start_time??'',0,5) }}-{{ substr($absence->seance->end_time??'',0,5) }}</td><td>{{ $absence->seance?->module?->name ?? '—' }}</td><td><span class="badge bg-{{ $absence->status=='present' ? 'success' : ($absence->status=='justified' ? 'info' : ($absence->status=='late' ? 'warning' : 'danger')) }}">{{ $absence->status }}</span></td><td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td></tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4">Aucune absence.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $absences->links() }}
    </div>
    <div class="tab-pane fade" id="justifications">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Titre</th><th>Statut</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($student->justifications as $j)
                        <tr><td>{{ $j->title }}</td><td><span class="badge bg-{{ $j->status=='accepted' ? 'success' : ($j->status=='rejected' ? 'danger' : 'warning') }}">{{ $j->status }}</span></td><td>{{ $j->created_at->format('d/m/Y') }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Aucune justification.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="reclamations">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Titre</th><th>Statut</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($student->reclamations as $r)
                        <tr><td>{{ $r->title }}</td><td><span class="badge bg-{{ $r->status=='resolved' ? 'success' : ($r->status=='rejected' ? 'danger' : ($r->status=='in_review' ? 'info' : 'warning')) }}">{{ $r->status }}</span></td><td>{{ $r->created_at->format('d/m/Y') }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Aucune réclamation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
