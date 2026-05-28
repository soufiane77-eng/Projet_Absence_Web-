@extends('layouts.admin')
@section('title', 'Modifier l\'Absence')
@section('page-header', 'Modifier l\'absence')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.absences.update', $absence) }}" method="POST">
            @csrf @method('PUT')
            <p><strong>Étudiant:</strong> {{ $absence->student->full_name }} | <strong>Séance:</strong> {{ $absence->seance->date->format('d/m/Y') }} {{ substr($absence->seance->start_time,0,5) }}</p>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="present" {{ old('status',$absence->status)=='present'?'selected':''}}>Présent</option>
                        <option value="absent" {{ old('status',$absence->status)=='absent'?'selected':''}}>Absent</option>
                        <option value="late" {{ old('status',$absence->status)=='late'?'selected':''}}>Retard</option>
                        <option value="justified" {{ old('status',$absence->status)=='justified'?'selected':''}}>Justifié</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-control">{{ old('notes', $absence->notes) }}</textarea></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mettre à jour</button>
                <a href="{{ route('admin.absences.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
