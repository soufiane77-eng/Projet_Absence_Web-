@extends('layouts.admin')
@section('title', 'Student Dashboard')
@section('page-header', 'Mon Tableau de Bord')
@section('content')
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="row g-4 mb-4">
    <div class="col-md-3"><x-stat-card title="Total Absences" :value="$stats['total_absences']" icon="fa-user-times" color="danger" /></div>
    <div class="col-md-3"><x-stat-card title="Non Justifiées" :value="$stats['unjustified_absences']" icon="fa-exclamation-triangle" color="warning" /></div>
    <div class="col-md-3"><x-stat-card title="Justifiées" :value="$stats['justified_absences']" icon="fa-check-circle" color="success" /></div>
    <div class="col-md-3"><x-stat-card title="En Attente" :value="$stats['pending_justifications']" icon="fa-clock" color="info" /></div>
</div>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Absences Récentes</h6>
                <a href="{{ route('student.absences.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Date</th><th>Module</th><th>Statut</th></tr></thead>
                    <tbody>
                        @forelse($recentAbsences as $absence)
                        <tr>
                            <td>{{ $absence->seance->date->format('d/m/Y') }}</td>
                            <td>{{ $absence->seance->module->name }}</td>
                            <td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4">Aucune absence récente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Séances à Venir</h6></div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Date</th><th>Horaire</th><th>Module</th><th>Classe</th><th>Semestre</th><th>Salle</th></tr></thead>
                    <tbody>
                        @forelse($upcomingSeances as $seance)
                        <tr>
                            <td>{{ $seance->date->format('d/m/Y') }}</td>
                            <td>{{ substr($seance->start_time,0,5) }}-{{ substr($seance->end_time,0,5) }}</td>
                            <td>{{ $seance->module->name }}</td>
                            <td>{{ $seance->classe->name ?? $seance->module->classe->name ?? '—' }}</td>
                            <td>{{ $seance->module->semester->name ?? '—' }}</td>
                            <td>{{ $seance->room ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4">Aucune séance à venir.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
