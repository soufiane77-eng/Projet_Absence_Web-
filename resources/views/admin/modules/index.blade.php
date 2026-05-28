@extends('layouts.admin')
@section('title', 'Gestion des Modules')
@section('page-header', 'Modules')
@section('page-actions')
    <a href="{{ route('admin.modules.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouveau Module</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}"></div>
            <div class="col-md-3">
                <select name="semester_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les semestres</option>
                    @foreach($semesters as $id=>$name)
                    <option value="{{ $id }}" {{ request('semester_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $id=>$name)
                    <option value="{{ $id }}" {{ request('class_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Code</th><th>Nom</th><th>Professeur</th><th>Classe</th><th>Semestre</th><th>Coeff.</th><th>Heures</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($modules as $module)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $module->code }}</span></td>
                        <td><a href="{{ route('admin.modules.show', $module) }}" class="fw-medium">{{ $module->name }}</a></td>
                        <td>
                            @if($module->teachers->isNotEmpty())
                                @foreach($module->teachers as $teacher)
                                    <span class="badge bg-info">{{ $teacher->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td><a href="{{ route('admin.modules.index') }}">{{ $module->classe->name }}</a></td>
                        <td>{{ $module->semester?->name ?? '—' }}</td>
                        <td>{{ $module->coefficient }}</td>
                        <td>{{ $module->total_hours }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.modules.show', $module) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4">Aucun module trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $modules->links() }}
    </div>
</div>
@endsection
