@extends('layouts.admin')
@section('title', 'Créer un Élément')
@section('page-header', 'Nouvel Élément')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.elements.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-6"><label class="form-label">Code <span class="text-danger">*</span></label><input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>@error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Module <span class="text-danger">*</span></label>
                    <select name="module_id" class="form-select @error('module_id') is-invalid @enderror" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($modules as $id=>$name)
                        <option value="{{ $id }}" {{ old('module_id', request('module_id'))==$id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('module_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4"><label class="form-label">Coefficient</label><input type="number" name="coefficient" class="form-control" value="{{ old('coefficient', 1) }}" min="1"></div>
                <div class="col-md-4"><label class="form-label">Heures</label><input type="number" name="total_hours" class="form-control" value="{{ old('total_hours', 30) }}" min="1"></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.elements.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
