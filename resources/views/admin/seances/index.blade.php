@extends('layouts.admin')
@section('title', 'Gestion des Séances')
@section('page-header', 'Séances')
@section('page-actions')
    <a href="{{ route('admin.seances.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Séance</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-2"><select name="semester_id" class="form-select" onchange="this.form.submit()"><option value="">Tous semestres</option>@foreach($semesters as $id=>$name)<option value="{{ $id }}" {{ request('semester_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="module_id" class="form-select" onchange="this.form.submit()"><option value="">Tous les modules</option>@foreach($modules as $id=>$name)<option value="{{ $id }}" {{ request('module_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="class_id" class="form-select" onchange="this.form.submit()"><option value="">Toutes les classes</option>@foreach($classes as $id=>$name)<option value="{{ $id }}" {{ request('class_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" placeholder="Du" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" placeholder="Au" value="{{ request('date_to') }}"></div>
            <div class="col-md-0"><button class="btn btn-outline-secondary"><i class="fa fa-filter"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Date</th><th>Horaire</th><th>Module</th><th>Classe</th><th>Type</th><th>Enseignant</th><th>Salle</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($seances as $seance)
                    <tr>
                        <td>{{ $seance->date->format('d/m/Y') }}</td>
                        <td>{{ substr($seance->start_time,0,5) }}-{{ substr($seance->end_time,0,5) }}</td>
                        <td><a href="{{ route('admin.modules.show', $seance->module) }}">{{ $seance->module->name }}</a></td>
                        <td>{{ $seance->classe->name }}</td>
                        <td><span class="badge bg-secondary">{{ $seance->type }}</span></td>
                        <td>{{ $seance->teacher?->name ?? '—' }}</td>
                        <td>{{ $seance->room ?? '—' }}</td>
                        <td>{!! $seance->status=='completed'?'<span class="badge bg-success">Terminée</span>':($seance->status=='cancelled'?'<span class="badge bg-danger">Annulée</span>':'<span class="badge bg-warning">Planifiée</span>') !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.seances.index') }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.seances.edit', $seance) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.seances.destroy', $seance) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4">Aucune séance trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $seances->links() }}
    </div>
</div>
@endsection
