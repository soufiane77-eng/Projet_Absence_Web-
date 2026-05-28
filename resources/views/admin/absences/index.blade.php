@extends('layouts.admin')
@section('title', 'Gestion des Absences')
@section('page-header', 'Absences')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-2"><select name="semester_id" class="form-select" onchange="this.form.submit()"><option value="">Tous semestres</option>@foreach($semesters as $id=>$name)<option value="{{ $id }}" {{ request('semester_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="class_id" class="form-select" onchange="this.form.submit()"><option value="">Toutes classes</option>@foreach($classes as $id=>$name)<option value="{{ $id }}" {{ request('class_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-3"><select name="module_id" class="form-select" onchange="this.form.submit()"><option value="">Tous modules</option>@foreach($modules as $id=>$name)<option value="{{ $id }}" {{ request('module_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="status" class="form-select" onchange="this.form.submit()"><option value="">Tous</option><option value="present" {{ request('status')=='present'?'selected':''}}>Présent</option><option value="absent" {{ request('status')=='absent'?'selected':''}}>Absent</option><option value="late" {{ request('status')=='late'?'selected':''}}>Retard</option><option value="justified" {{ request('status')=='justified'?'selected':''}}>Justifié</option></select></div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Du"></div>
            <div class="col-md-1"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Au"></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Étudiant</th><th>Classe</th><th>Séance</th><th>Module</th><th>Date</th><th>Statut</th><th>Justifié</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($absences as $absence)
                    <tr>
                        <td><a href="{{ route('admin.students.show', $absence->student) }}">{{ $absence->student->full_name }}</a></td>
                        <td>{{ $absence->student->classe?->name ?? '—' }}</td>
                        <td><a href="{{ route('admin.seances.show', $absence->seance) }}">{{ $absence->seance->date->format('d/m') }} {{ substr($absence->seance->start_time,0,5) }}</a></td>
                        <td>{{ $absence->module?->name ?? $absence->seance->module->name }}</td>
                        <td>{{ $absence->seance->date->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td>
                        <td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.absences.show', $absence) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('admin.absences.edit', $absence) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4">Aucune absence trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $absences->links() }}
    </div>
</div>
@endsection
