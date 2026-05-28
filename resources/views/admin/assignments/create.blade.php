@extends('layouts.admin')
@section('title', 'Nouvelle Affectation')
@section('page-header', 'Nouvelle Affectation')
@section('content')
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i>
    Les affectations permettent d'assigner un enseignant à un module dans une classe spécifique, avec le type de cours (cours, TD, TP)
    <br><strong>NB il faut avant l affectation de creer tout dabord un compte de professuer en allant vers compte</strong>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.assignments.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Enseignant <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select" id="teacher-select" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($teachers as $id=>$name)
                        <option value="{{ $id }}" {{ old('user_id')==$id?'selected':''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Module <span class="text-danger">*</span></label>
                    <select name="module_id" class="form-select" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($modules as $id=>$name)
                        <option value="{{ $id }}" {{ old('module_id')==$id?'selected':''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Classe <span class="text-danger">*</span></label>
                    <select name="class_id" class="form-select" required>
                        <option value="">— Sélectionner —</option>
                        @foreach($classes as $id=>$name)
                        <option value="{{ $id }}" {{ old('class_id')==$id?'selected':''}}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Type <span class="text-danger">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="cours" {{ old('type')=='cours'?'selected':''}}>Cours</option>
                        <option value="td" {{ old('type')=='td'?'selected':''}}>TD</option>
                        <option value="tp" {{ old('type')=='tp'?'selected':''}}>TP</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('teacher-select');
    let currentValue = select.value;

    setInterval(function() {
        fetch('{{ route("admin.assignments.teachers-json") }}')
            .then(r => r.json())
            .then(teachers => {
                const selected = select.value;
                select.innerHTML = '<option value="">— Sélectionner —</option>';
                teachers.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.id;
                    opt.textContent = t.name;
                    if (t.id == selected) opt.selected = true;
                    select.appendChild(opt);
                });
            })
            .catch(() => {});
    }, 5000);
});
</script>
@endpush
