@extends('layouts.admin')
@section('title', 'Modifier l\'Enseignant')
@section('page-header', 'Modifier : '.$teacher->full_name)
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">CIN <span class="text-danger">*</span></label><input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" value="{{ old('cin', $teacher->cin) }}" required>@error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $teacher->last_name) }}" required>@error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Prénom <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $teacher->first_name) }}" required>@error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Nom (Arabe)</label><input type="text" name="last_name_ar" class="form-control" value="{{ old('last_name_ar', $teacher->last_name_ar) }}"></div>
                <div class="col-md-4"><label class="form-label">Prénom (Arabe)</label><input type="text" name="first_name_ar" class="form-control" value="{{ old('first_name_ar', $teacher->first_name_ar) }}"></div>
                <div class="col-md-4"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $teacher->email) }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Téléphone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}"></div>
                <div class="col-md-4"><label class="form-label">Spécialité</label><input type="text" name="specialty" class="form-control" value="{{ old('specialty', $teacher->specialty) }}"></div>
                <div class="col-md-4">
                    <label class="form-label">Grade</label>
                    <select name="grade" class="form-select">
                        <option value="">— Sélectionner —</option>
                        <option value="Professeur" {{ old('grade', $teacher->grade)=='Professeur' ? 'selected':'' }}>Professeur</option>
                        <option value="MCF" {{ old('grade', $teacher->grade)=='MCF' ? 'selected':'' }}>MCF</option>
                        <option value="PA" {{ old('grade', $teacher->grade)=='PA' ? 'selected':'' }}>PA</option>
                        <option value="Assistant" {{ old('grade', $teacher->grade)=='Assistant' ? 'selected':'' }}>Assistant</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Adresse</label><textarea name="address" rows="2" class="form-control">{{ old('address', $teacher->address) }}</textarea></div>
                <div class="col-md-3">
                    <label class="form-label">Photo</label>
                    @if($teacher->photo)<div class="mb-2"><img src="{{ asset('storage/'.$teacher->photo) }}" style="height:50px" class="rounded"></div>@endif
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <small class="text-muted">Laissez vide pour conserver.</small>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $teacher->is_active) ? 'checked':'' }} id="isActive">
                        <label class="form-check-label" for="isActive">Actif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mettre à jour</button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
