@extends('layouts.admin')
@section('title', 'Réinitialiser le mot de passe')
@section('page-header', 'Réinitialiser le mot de passe')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="mb-4">
            <strong>Utilisateur :</strong> {{ $user->name }}
            <span class="badge bg-{{ $user->role=='teacher'?'info':'primary' }} ms-2">{{ $user->role }}</span>
        </div>
        <form action="{{ route('admin.accounts.reset-password', $user) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Minimum 8 caractères">
                    <small class="text-muted">Minimum 8 caractères.</small>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required placeholder="Confirmer">
                    @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> Réinitialiser</button>
                <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
