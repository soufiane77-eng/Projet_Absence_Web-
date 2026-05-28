@extends('layouts.admin')
@section('title', 'Gestion des Éléments')
@section('page-header', 'Éléments')
@section('page-actions')
    <a href="{{ route('admin.elements.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvel Élément</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}"></div>
            <div class="col-md-4">
                <select name="module_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les modules</option>
                    @foreach($modules as $id=>$name)
                    <option value="{{ $id }}" {{ request('module_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Code</th><th>Nom</th><th>Module</th><th>Coeff.</th><th>Heures</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($elements as $element)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $element->code }}</span></td>
                        <td>{{ $element->name }}</td>
                        <td><a href="{{ route('admin.modules.show', $element->module) }}">{{ $element->module->name }}</a> ({{ $element->module->classe->name }})</td>
                        <td>{{ $element->coefficient }}</td>
                        <td>{{ $element->total_hours }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.elements.edit', $element) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.elements.destroy', $element) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucun élément trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $elements->links() }}
    </div>
</div>
@endsection
