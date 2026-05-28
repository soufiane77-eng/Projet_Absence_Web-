@extends('layouts.admin')
@section('title', 'Gestion des Enseignants')
@section('page-header', 'Enseignants')
@section('page-actions')
    <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvel Enseignant</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="CIN, Nom, Email..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>CIN</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Spécialité</th><th>Grade</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td><code>{{ $teacher->cin }}</code></td>
                        <td class="fw-medium">{{ $teacher->last_name }}</td>
                        <td>{{ $teacher->first_name }}</td>
                        <td><small>{{ $teacher->email }}</small></td>
                        <td>{{ $teacher->specialty ?? '—' }}</td>
                        <td><span class="badge bg-info">{{ $teacher->grade ?? '—' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">Aucun enseignant trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $teachers->links() }}
    </div>
</div>
@endsection
