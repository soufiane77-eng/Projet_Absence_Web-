@extends('layouts.admin')
@section('title', 'Créer un Compte Utilisateur')
@section('page-header', 'Nouveau Compte')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.accounts.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Type de compte <span class="text-danger">*</span></label>
                    <select name="type" id="accountType" class="form-select" required onchange="toggleType()">
                        <option value="">— Sélectionner —</option>
                        <option value="teacher" {{ old('type')=='teacher'?'selected':''}}>Enseignant</option>
                        <option value="student" {{ old('type')=='student'?'selected':''}}>Étudiant</option>
                    </select>
                </div>
                <div class="col-md-6" id="teacherSelect" style="display:none">
                    <label class="form-label">Enseignant sans compte <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($teachers as $id=>$name)
                        <option value="{{ $id }}" {{ old('teacher_id')==$id?'selected':''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6" id="studentSelect" style="display:none">
                    <label class="form-label">Étudiant sans compte <span class="text-danger">*</span></label>
                    <select name="student_id" class="form-select">
                        <option value="">— Sélectionner —</option>
                        @foreach($students as $id=>$name)
                        <option value="{{ $id }}" {{ old('student_id')==$id?'selected':''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" required placeholder="Ex: john.doe">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           required placeholder="Minimum 8 caractères">
                    <small class="text-muted">Minimum 8 caractères.</small>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Créer le compte</button>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function toggleType() {
    var type = document.getElementById('accountType').value;
    var teacherSelect = document.getElementById('teacherSelect');
    var studentSelect = document.getElementById('studentSelect');
    var showTeacher = type == 'teacher';
    var showStudent = type == 'student';
    teacherSelect.style.display = showTeacher ? 'block' : 'none';
    studentSelect.style.display = showStudent ? 'block' : 'none';
    teacherSelect.querySelector('select').disabled = !showTeacher;
    studentSelect.querySelector('select').disabled = !showStudent;
}
toggleType();
</script>
@endpush
