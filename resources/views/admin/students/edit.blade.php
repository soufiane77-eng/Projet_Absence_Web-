@extends('layouts.admin')
@section('title', 'Modifier l\'Étudiant')
@section('page-header', 'Modifier : '.$student->full_name)
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Code Massar <span class="text-danger">*</span></label><input type="text" name="cne" class="form-control @error('cne') is-invalid @enderror" value="{{ old('cne', $student->cne) }}" required>@error('cne')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">CIN <span class="text-danger">*</span></label><input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" value="{{ old('cin', $student->cin) }}" required>@error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label">Nom <span class="text-danger">*</span></label><input type="text" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required></div>
                <div class="col-md-4"><label class="form-label">Prénom <span class="text-danger">*</span></label><input type="text" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required></div>
                <div class="col-md-4"><label class="form-label">Genre</label><select name="gender" class="form-select"><option value="">—</option><option value="male" {{ old('gender',$student->gender)=='male'?'selected':''}}>Masculin</option><option value="female" {{ old('gender',$student->gender)=='female'?'selected':''}}>Féminin</option></select></div>
                <div class="col-md-4"><label class="form-label">Nom (Arabe)</label><input type="text" name="last_name_ar" class="form-control" value="{{ old('last_name_ar', $student->last_name_ar) }}"></div>
                <div class="col-md-4"><label class="form-label">Prénom (Arabe)</label><input type="text" name="first_name_ar" class="form-control" value="{{ old('first_name_ar', $student->first_name_ar) }}"></div>
                <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}"></div>
                <div class="col-md-3"><label class="form-label">Téléphone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}"></div>
                <div class="col-md-3"><label class="form-label">Date naissance</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}"></div>
                <div class="col-md-3"><label class="form-label">Lieu naissance</label><input type="text" name="birth_place" class="form-control" value="{{ old('birth_place', $student->birth_place) }}"></div>
                <div class="col-md-3"><label class="form-label">Classe</label><select name="class_id" class="form-select"><option value="">—</option>@foreach($classes as $id=>$name)<option value="{{ $id }}" {{ old('class_id',$student->class_id)==$id?'selected':''}}>{{ $name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Adresse</label><textarea name="address" rows="2" class="form-control">{{ old('address', $student->address) }}</textarea></div>
                <div class="col-md-3"><label class="form-label">Photo</label>@if($student->photo)<div class="mb-2"><img src="{{ asset('storage/'.$student->photo) }}" style="height:50px" class="rounded"></div>@endif<input type="file" name="photo" class="form-control" accept="image/*"><small class="text-muted">Laissez vide pour conserver.</small></div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mettre à jour</button>
                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
