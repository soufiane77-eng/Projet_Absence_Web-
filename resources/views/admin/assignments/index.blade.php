@extends('layouts.admin')
@section('title', 'Affectations des Enseignants')
@section('page-header', 'Affectations')
@section('page-actions')
    <a href="{{ route('admin.assignments.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Affectation</a>
@endsection
@section('content')
<p class="text-muted small mb-3">Les affectations permettent d'assigner un enseignant à un module dans une classe spécifique, avec le type de cours (cours, TD, TP).</p>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Enseignant</th><th>Module</th><th>Classe</th><th>Type</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($assignments as $a)
                    <tr>
                        <td>{{ $a->user?->name ?? '—' }}</td>
                        <td>{{ $a->module?->name ?? '—' }}</td>
                        <td>{{ $a->classe?->name ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $a->type }}</span></td>
                        <td class="text-end">
                            <form action="{{ route('admin.assignments.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette affectation ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Aucune affectation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assignments->links() }}
    </div>
</div>
@endsection
