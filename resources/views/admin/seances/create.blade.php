@extends('layouts.admin')
@section('title', 'Créer une Séance')
@section('page-header', 'Nouvelle Séance')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.seances.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Module <span class="text-danger">*</span></label><select name="module_id" class="form-select" required>@foreach($modules as $id=>$name)<option value="{{ $id }}" {{ old('module_id',request('module_id'))==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Classe <span class="text-danger">*</span></label><select name="class_id" class="form-select" required>@foreach($classes as $id=>$name)<option value="{{ $id }}" {{ old('class_id',request('class_id'))==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label">Enseignant</label><select name="teacher_id" class="form-select"><option value="">—</option>@foreach($teachers as $id=>$name)<option value="{{ $id }}" {{ old('teacher_id')==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
                <div class="col-md-3"><label class="form-label">Type <span class="text-danger">*</span></label><select name="type" class="form-select" required><option value="cours" {{ old('type')=='cours'?'selected':''}}>Cours</option><option value="td" {{ old('type')=='td'?'selected':''}}>TD</option><option value="tp" {{ old('type')=='tp'?'selected':''}}>TP</option></select></div>
                <div class="col-md-3"><label class="form-label">Date <span class="text-danger">*</span></label><input type="date" name="date" class="form-control" value="{{ old('date') }}" required></div>
                <div class="col-md-3"><label class="form-label">Début <span class="text-danger">*</span></label><input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required></div>
                <div class="col-md-3"><label class="form-label">Fin <span class="text-danger">*</span></label><input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required></div>
                <div class="col-md-3"><label class="form-label">Salle</label><input type="text" name="room" class="form-control" value="{{ old('room') }}"></div>
                <div class="col-md-3"><label class="form-label">Statut</label><select name="status" class="form-select"><option value="scheduled">Planifiée</option><option value="completed">Terminée</option><option value="cancelled">Annulée</option></select></div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.seances.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
