@extends('layouts.admin')
@section('title', 'Gestion des Filières')
@section('page-header', 'Filières')
@section('page-actions')
    <a href="{{ route('admin.filieres.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Filière</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                    @if(request('search'))<a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-danger"><i class="fa fa-times"></i></a>@endif
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Code</th><th>Nom</th><th>Coordinateur</th><th class="text-center">Classes</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($filieres as $filiere)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $filiere->code }}</span></td>
                        <td><a href="{{ route('admin.filieres.show', $filiere) }}" class="text-decoration-none fw-medium">{{ $filiere->name }}</a></td>
                        <td>{{ $filiere->coordinator?->name ?? 'Aucun coordinateur' }}</td>
                        <td class="text-center"><span class="badge bg-info">{{ $filiere->classes->count() }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.filieres.show', $filiere) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.filieres.destroy', $filiere) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette filière ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune filière trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">{{ $filieres->links() }}</div>
    </div>
</div>
@endsection
