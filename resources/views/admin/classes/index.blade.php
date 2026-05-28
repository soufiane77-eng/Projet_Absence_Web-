@extends('layouts.admin')
@section('title', 'Gestion des Classes')
@section('page-header', 'Classes')
@section('page-actions')
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Classe</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un étudiant..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="filiere_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $id=>$name)
                    <option value="{{ $id }}" {{ request('filiere_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Code</th><th>Nom</th><th>Filière</th><th>Niveau</th><th class="text-center">Étudiants</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($classes as $classe)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $classe->code }}</span></td>
                        <td><a href="{{ route('admin.modules.index') }}" class="fw-medium">{{ $classe->name }}</a></td>
                        <td>{{ $classe->filiere?->name ?? '—' }}</td>
                        <td>{{ $classe->level ?? '—' }}</td>
                        <td class="text-center">{{ $classe->students->count() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.modules.index') }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune classe.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $classes->links() }}
    </div>
</div>
@endsection
