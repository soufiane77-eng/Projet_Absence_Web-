@extends('layouts.admin')
@section('title', 'Ajouter un Étudiant')
@section('page-header', 'Nouvel Étudiant')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Code Massar <span class="text-danger">*</span></label><input type="text" name="cne" class="form-control @error('cne') is-invalid @enderror" value="{{ old('cne') }}" required>@error('cne')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">CIN <span class="text-danger">*</span></label><input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" value="{{ old('cin') }}" required>@error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>@error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Prénom <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>@error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Genre</label><select name="gender" class="form-select"><option value="">—</option><option value="male" {{ old('gender')=='male' ? 'selected':'' }}>Masculin</option><option value="female" {{ old('gender')=='female' ? 'selected':'' }}>Féminin</option></select></div>
                <div class="col-md-4"><label class="form-label">Nom (Arabe)</label><input type="text" name="last_name_ar" class="form-control" value="{{ old('last_name_ar') }}"></div>
                <div class="col-md-4"><label class="form-label">Prénom (Arabe)</label><input type="text" name="first_name_ar" class="form-control" value="{{ old('first_name_ar') }}"></div>
                <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
                <div class="col-md-3"><label class="form-label">Téléphone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                <div class="col-md-3"><label class="form-label">Date naissance</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"></div>
                <div class="col-md-3"><label class="form-label">Lieu naissance</label><input type="text" name="birth_place" class="form-control" value="{{ old('birth_place') }}"></div>
                <div class="col-md-3"><label class="form-label">Classe</label>
                    <select name="class_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($classes as $id=>$name)
                        <option value="{{ $id }}" {{ old('class_id', request('class_id'))==$id ? 'selected':'' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Adresse</label><textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea></div>
                <div class="col-md-3"><label class="form-label">Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
