@extends('layouts.admin')
@section('title', 'Créer un Module')
@section('page-header', 'Nouveau Module')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.modules.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Code <span class="text-danger">*</span></label><input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>@error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Classe <span class="text-danger">*</span></label>
                    <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($classes as $id=>$name)
                        <option value="{{ $id }}" {{ old('class_id', request('class_id'))==$id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4"><label class="form-label">Semestre</label>
                    <select name="semester_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($semesters as $id=>$name)
                        <option value="{{ $id }}" {{ old('semester_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><label class="form-label">Coefficient</label><input type="number" name="coefficient" class="form-control" value="{{ old('coefficient', 1) }}" min="1" max="10"></div>
                <div class="col-md-2"><label class="form-label">Heures</label><input type="number" name="total_hours" class="form-control" value="{{ old('total_hours', 30) }}" min="1"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.modules.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
