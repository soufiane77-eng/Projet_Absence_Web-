@extends('layouts.admin')
@section('title', 'Appel - '.$seance->module->name)
@section('page-header', 'Appel : '.$seance->module->name.' - '.$seance->classe->name.' ('.$seance->date->format('d/m/Y').')')
@section('page-actions')
    <a href="{{ route('teacher.seances.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Retour</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form id="absenceForm" action="{{ route('teacher.seances.store-absences', $seance) }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>CNE</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th class="text-center" style="width:300px">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php $existing = $existingAbsences->get($student->id); @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $student->cne }}</code></td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->first_name }}</td>
                            <td class="text-center">
                                <input type="hidden" name="students[{{ $index }}][student_id]" value="{{ $student->id }}">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-success status-btn {{ (!$existing || $existing->status=='present') ? 'btn-success active' : '' }}" data-status="present" onclick="setStatus(this, {{ $index }})">
                                        <i class="fa fa-check"></i> Présent
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger status-btn {{ ($existing && $existing->status=='absent') ? 'btn-danger active' : '' }}" data-status="absent" onclick="setStatus(this, {{ $index }})">
                                        <i class="fa fa-times"></i> Absent
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning status-btn {{ ($existing && $existing->status=='late') ? 'btn-warning active' : '' }}" data-status="late" onclick="setStatus(this, {{ $index }})">
                                        <i class="fa fa-clock"></i> Retard
                                    </button>
                                </div>
                                <input type="hidden" name="students[{{ $index }}][status]" class="status-input" value="{{ $existing ? $existing->status : 'present' }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Enregistrer les absences</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function setStatus(btn, index) {
    var group = btn.closest('.btn-group');
    group.querySelectorAll('.status-btn').forEach(function(b) {
        b.classList.remove('btn-success', 'btn-danger', 'btn-warning', 'active');
        b.classList.add('btn-outline-success', 'btn-outline-danger', 'btn-outline-warning');
    });
    btn.classList.remove('btn-outline-success', 'btn-outline-danger', 'btn-outline-warning');
    if (btn.dataset.status == 'present') btn.classList.add('btn-success');
    else if (btn.dataset.status == 'absent') btn.classList.add('btn-danger');
    else if (btn.dataset.status == 'late') btn.classList.add('btn-warning');
    btn.classList.add('active');
    var form = document.getElementById('absenceForm');
    var inputs = form.querySelectorAll('input[name="students[' + index + '][status]"]');
    inputs.forEach(function(inp) { inp.value = btn.dataset.status; });
}
</script>
@endpush
