@extends('layouts.admin')
@section('title', 'Créer une Classe')
@section('page-header', 'Nouvelle Classe')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.classes.store') }}" method="POST">
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
                    <label class="form-label">Filière <span class="text-danger">*</span></label>
                    <select name="filiere_id" class="form-select @error('filiere_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($filieres as $id=>$name)
                        <option value="{{ $id }}" {{ old('filiere_id', request('filiere_id'))==$id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('filiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Niveau</label>
                    <input type="text" name="level" class="form-control" value="{{ old('level') }}" placeholder="ex: Bac+1">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
