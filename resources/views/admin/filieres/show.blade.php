@extends('layouts.admin')
@section('title', $filiere->name)
@section('page-header', $filiere->name)
@section('page-actions')
    <a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Modifier</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="fw-bold mb-0">Détails</h6></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th class="text-muted">Code</th><td><span class="badge bg-secondary">{{ $filiere->code }}</span></td></tr>
                    <tr><th class="text-muted">Coordinateur</th><td>{{ $filiere->coordinator?->name ?? 'Aucun coordinateur pour cette filière' }}</td></tr>
                    <tr><th class="text-muted">Classes</th><td><span class="badge bg-info">{{ $filiere->classes->count() }}</span></td></tr>
                    <tr><th class="text-muted">Créée le</th><td>{{ $filiere->created_at->format('d/m/Y') }}</td></tr>
                </table>
                @if($filiere->description)
                <hr><p class="text-muted mb-0">{{ $filiere->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Classes ({{ $filiere->classes->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Code</th><th>Nom</th><th>Niveau</th><th class="text-center">Étudiants</th></tr></thead>
                    <tbody>
                        @forelse($filiere->classes as $classe)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $classe->code }}</span></td>
                            <td><a href="{{ route('admin.modules.index') }}">{{ $classe->name }}</a></td>
                            <td>{{ $classe->level ?? '—' }}</td>
                            <td class="text-center">{{ $classe->students->count() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Aucune classe.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
