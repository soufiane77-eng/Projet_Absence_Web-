@extends('layouts.admin')
@section('title', 'Mes Absences')
@section('page-header', 'Absences enregistrées')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-2"><select name="status" class="form-select" onchange="this.form.submit()"><option value="">Tous</option><option value="present" {{ request('status')=='present'?'selected':''}}>Présent</option><option value="absent" {{ request('status')=='absent'?'selected':''}}>Absent</option><option value="late" {{ request('status')=='late'?'selected':''}}>Retard</option></select></div>
            <div class="col-md-2"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
            <div class="col-md-2"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Étudiant</th><th>Classe</th><th>Date</th><th>Module</th><th>Statut</th><th>Justification</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($absences as $absence)
                    <tr>
                        <td><a href="{{ route('admin.students.show', $absence->student) }}">{{ $absence->student->full_name }}</a></td>
                        <td>{{ $absence->student->classe?->name ?? '—' }}</td>
                        <td>{{ $absence->seance->date->format('d/m/Y') }}</td>
                        <td>{{ $absence->seance->module->name }}</td>
                        <td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td>
                        <td>
                            @if($absence->justification)
                                <span class="badge bg-{{ $absence->justification->status=='accepted'?'success':($absence->justification->status=='rejected'?'danger':'warning') }} small">
                                    {{ $absence->justification->status=='accepted'?'Acceptée':($absence->justification->status=='rejected'?'Rejetée':'En attente') }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('teacher.absences.show', $absence) }}" class="btn btn-sm btn-outline-info">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">Aucune absence trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $absences->links() }}
    </div>
</div>
@endsection
