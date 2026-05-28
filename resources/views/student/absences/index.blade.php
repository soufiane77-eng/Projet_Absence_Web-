@extends('layouts.admin')
@section('title', 'Mes Absences')
@section('page-header', 'Mes Absences')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Date</th><th>Séance</th><th>Module</th><th>Statut</th><th>Justifié</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($absences as $absence)
                    <tr>
                        <td>{{ $absence->seance->date->format('d/m/Y') }}</td>
                        <td>{{ substr($absence->seance->start_time,0,5) }}-{{ substr($absence->seance->end_time,0,5) }}</td>
                        <td>{{ $absence->seance->module->name }}</td>
                        <td><span class="badge bg-{{ $absence->status=='present'?'success':($absence->status=='justified'?'info':($absence->status=='late'?'warning':'danger')) }}">{{ $absence->status }}</span></td>
                        <td>{!! $absence->is_justified ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('student.absences.show', $absence) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                            @if(!$absence->is_justified && $absence->status!='present')
                            <a href="{{ route('student.justifications.create', ['absence_id'=>$absence->id]) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-file"></i> Justifier</a>
                            <a href="{{ route('student.reclamations.create', ['absence_id'=>$absence->id]) }}" class="btn btn-sm btn-outline-warning"><i class="fa fa-exclamation"></i> Réclamer</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune absence trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $absences->links() }}
    </div>
</div>
@endsection
