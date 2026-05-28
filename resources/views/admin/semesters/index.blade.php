@extends('layouts.admin')
@section('title', 'Gestion des Semestres')
@section('page-header', 'Semestres')
@section('page-actions')
    <a href="{{ route('admin.semesters.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouveau Semestre</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Nom</th><th>Date début</th><th>Date fin</th><th>Statut</th><th>Modules</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($semesters as $semester)
                    <tr>
                        <td class="fw-medium">{{ $semester->name }}</td>
                        <td>{{ $semester->start_date->format('d/m/Y') }}</td>
                        <td>{{ $semester->end_date->format('d/m/Y') }}</td>
                        <td>{!! $semester->is_active ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-secondary">Inactif</span>' !!}</td>
                        <td><span class="badge bg-info">{{ $semester->modules->count() }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.semesters.show', $semester) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.semesters.edit', $semester) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.semesters.destroy', $semester) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucun semestre trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $semesters->links() }}
    </div>
</div>
@endsection
