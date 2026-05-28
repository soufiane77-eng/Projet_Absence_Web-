@extends('layouts.admin')
@section('title', 'Mes Séances')
@section('page-header', 'Mes Séances')
@section('page-actions')
    <a href="{{ route('teacher.seances.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Séance</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-3"><select name="class_id" class="form-select" onchange="this.form.submit()"><option value="">Toutes classes</option>@foreach($classes as $id=>$name)<option value="{{ $id }}" {{ request('class_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Du"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Au"></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Date</th><th>Horaire</th><th>Module</th><th>Classe</th><th>Type</th><th>Salle</th><th>Statut</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($seances as $seance)
                    <tr>
                        <td>{{ $seance->date->format('d/m/Y') }}</td>
                        <td>{{ substr($seance->start_time,0,5) }}-{{ substr($seance->end_time,0,5) }}</td>
                        <td>{{ $seance->module->name }}</td>
                        <td>{{ $seance->classe->name }}</td>
                        <td><span class="badge bg-secondary">{{ $seance->type }}</span></td>
                        <td>{{ $seance->room ?? '—' }}</td>
                        <td>{!! $seance->status=='completed'?'<span class="badge bg-success">Terminée</span>':($seance->status=='cancelled'?'<span class="badge bg-danger">Annulée</span>':'<span class="badge bg-warning">Planifiée</span>') !!}</td>
                        <td class="text-end">
                            <a href="{{ route('teacher.seances.mark-absences', $seance) }}" class="btn btn-sm btn-{{ $seance->status=='completed'?'outline-secondary':'primary' }}" {{ $seance->status=='completed'?'disabled':'' }}>
                                <i class="fa fa-check-square"></i> Appel
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4">Aucune séance trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $seances->links() }}
    </div>
</div>
@endsection
