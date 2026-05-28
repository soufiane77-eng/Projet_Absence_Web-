@extends('layouts.admin')
@section('title', 'Gestion des Étudiants')
@section('page-header', 'Étudiants')
@section('page-actions')
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvel Étudiant</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Code Massar, Nom..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $id=>$name)
                    <option value="{{ $id }}" {{ request('class_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Code Massar</th><th>Nom</th><th>Prénom</th><th>Classe</th><th>Email</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td><code>{{ $student->cne }}</code></td>
                        <td class="fw-medium">{{ $student->last_name }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->classe?->name ?? '—' }}</td>
                        <td><small>{{ $student->email ?? '—' }}</small></td>
                        <td class="text-end">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucun étudiant trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $students->links() }}
    </div>
</div>
@endsection
