@extends('layouts.admin')
@section('title', 'Modifier le Semestre')
@section('page-header', 'Modifier : '.$semester->name)
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.semesters.update', $semester) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $semester->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label">Date début <span class="text-danger">*</span></label><input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $semester->start_date->format('Y-m-d')) }}" required>@error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3"><label class="form-label">Date fin <span class="text-danger">*</span></label><input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $semester->end_date->format('Y-m-d')) }}" required>@error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" id="isActive" {{ old('is_active', $semester->is_active) ? 'checked':'' }}>
                        <label class="form-check-label" for="isActive">Semestre actif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mettre à jour</button>
                <a href="{{ route('admin.semesters.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
