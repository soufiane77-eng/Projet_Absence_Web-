@extends('layouts.admin')
@section('title', $semester->name)
@section('page-header', $semester->name)
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Détails</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Début</th><td>{{ $semester->start_date->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Fin</th><td>{{ $semester->end_date->format('d/m/Y') }}</td></tr>
                    <tr><th class="text-muted">Statut</th><td>{!! $semester->is_active ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-secondary">Inactif</span>' !!}</td></tr>
                    <tr><th class="text-muted">Modules</th><td><span class="badge bg-info">{{ $semester->modules->count() }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Modules</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Code</th><th>Module</th><th>Classe</th><th>Coeff.</th></tr></thead>
                    <tbody>
                        @forelse($semester->modules as $module)
                        <tr><td><span class="badge bg-secondary">{{ $module->code }}</span></td><td><a href="{{ route('admin.modules.show', $module) }}">{{ $module->name }}</a></td><td>{{ $module->classe->name }}</td><td>{{ $module->coefficient }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Aucun module.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
