@extends('layouts.admin')
@section('title', 'Soumettre une Réclamation')
@section('page-header', 'Nouvelle Réclamation')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('student.reclamations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Absence concernée</label>
                    <select name="absence_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($absences as $a)
                        <option value="{{ $a->id }}" {{ old('absence_id', $absenceId)==$a->id?'selected':''}}>
                            {{ $a->seance->date->format('d/m/Y') }} - {{ $a->seance->module->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Soumettre</button>
                <a href="{{ route('student.reclamations.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
