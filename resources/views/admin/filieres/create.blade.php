@extends('layouts.admin')
@section('title', 'Créer une Filière')
@section('page-header', 'Nouvelle Filière')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.filieres.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Coordinateur</label>
                    <select name="coordinator_id" class="form-select">
                        <option value="">Aucun coordinateur</option>
                        <optgroup label="Administrateurs">
                            @foreach(\App\Models\User::where('role','admin')->get() as $u)
                            <option value="{{ $u->id }}" {{ old('coordinator_id')==$u->id ? 'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Enseignants">
                            @foreach(\App\Models\User::where('role','teacher')->get() as $u)
                            <option value="{{ $u->id }}" {{ old('coordinator_id')==$u->id ? 'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
