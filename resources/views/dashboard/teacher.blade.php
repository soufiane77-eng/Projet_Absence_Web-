@extends('layouts.admin')
@section('title', 'Teacher Dashboard')
@section('page-header', 'Mon Tableau de Bord')
@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3"><x-stat-card title="Modules" :value="$stats['total_modules']" icon="fa-book" color="primary" /></div>
    <div class="col-md-3"><x-stat-card title="Classes" :value="$stats['total_classes']" icon="fa-building" color="info" /></div>
    <div class="col-md-3"><x-stat-card title="Séances à venir" :value="$stats['upcoming_seances']" icon="fa-calendar" color="success" /></div>
    <div class="col-md-3"><x-stat-card title="Absences aujourd'hui" :value="$todayAbsences" icon="fa-user-times" color="danger" /></div>
</div>
<div class="row g-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Séances Récentes</h6>
                <a href="{{ route('teacher.seances.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Date</th><th>Module</th><th>Classe</th><th>Type</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                        @forelse($recentSeances as $seance)
                        <tr>
                            <td>{{ $seance->date->format('d/m/Y') }}</td>
                            <td>{{ $seance->module->name }}</td>
                            <td>{{ $seance->classe->name }}</td>
                            <td><span class="badge bg-secondary">{{ $seance->type }}</span></td>
                            <td>{!! $seance->status=='completed'?'<span class="badge bg-success">Terminée</span>':'<span class="badge bg-warning">Planifiée</span>' !!}</td>
                            <td class="text-end">
                                <a href="{{ route('teacher.seances.mark-absences', $seance) }}" class="btn btn-sm btn-{{ $seance->status=='completed'?'outline-secondary':'primary' }}">
                                    <i class="fa fa-check-square"></i> Appel
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4">Aucune séance récente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
