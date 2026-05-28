@extends('layouts.admin')
@section('title', 'Soumettre une Justification')
@section('page-header', 'Nouvelle Justification')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('student.justifications.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Absence concernée</label>
                    <select name="absence_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($unjustifiedAbsences as $a)
                        <option value="{{ $a->id }}" {{ old('absence_id', $absenceId)==$a->id ? 'selected':'' }}>
                            {{ $a->seance->date->format('d/m/Y') }} - {{ $a->seance->module->name }} ({{ substr($a->seance->start_time,0,5) }})
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
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Document (jpg, png, pdf)</label>
                    <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">
                    @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Max 5 Mo</small>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Soumettre</button>
                <a href="{{ route('student.justifications.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
