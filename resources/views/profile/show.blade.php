@extends('layouts.' . (Auth::user()->role === 'admin' ? 'admin' : Auth::user()->role))
@section('title', 'Mon Profil')
@section('page-header', 'Mon Profil')
@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                             class="rounded-circle" width="120" height="120" style="object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center"
                             style="width: 120px; height: 120px;">
                            <img src="{{ asset('logo-ecocle.svg') }}" alt="Logo" width="80" height="80" style="object-fit:contain;">
                        </div>
                    @endif
                </div>
                <h5 class="fw-bold">{{ $user->name }}</h5>
                @php
                    $roleLabel = match($user->role) {
                        'admin' => 'Administrateur',
                        'teacher' => 'Enseignant',
                        'student' => 'Étudiant',
                        default => ucfirst($user->role),
                    };
                @endphp
                <span class="badge badge-role-{{ $user->role }} mb-3">{{ $roleLabel }}</span>

                <form action="{{ route(Auth::user()->role . '.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="mb-2">
                        <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                        <i class="fa fa-upload"></i> Changer l'avatar
                    </button>
                </form>

                <hr>
                <div class="text-start small">
                    <div class="mb-2"><strong><i class="fa fa-calendar"></i> Membre depuis</strong><br>{{ $user->created_at->format('d/m/Y') }}</div>
                    <div class="mb-2"><strong><i class="fa fa-sign-in-alt"></i> Dernière connexion</strong><br>{{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Jamais' }}</div>
                    <div><strong><i class="fa fa-globe"></i> Dernier IP</strong><br>{{ $user->last_login_ip ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0"><i class="fa fa-user-edit me-1"></i> Informations personnelles</h6>
            </div>
            <div class="card-body">
                <form action="{{ route(Auth::user()->role . '.profile.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                            <small class="text-muted">Le nom d'utilisateur ne peut pas être modifié.</small>
                        </div>
                    </div>
                    @if($user->student)
                    <hr>
                    <h6 class="fw-bold"><i class="fa fa-graduation-cap me-1"></i> Informations étudiant</h6>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4"><label class="form-label">Code Massar</label><input type="text" class="form-control" value="{{ $user->student->cne }}" disabled></div>
                        <div class="col-md-4"><label class="form-label">Classe</label><input type="text" class="form-control" value="{{ $user->student->classe?->name ?? '—' }}" disabled></div>
                    </div>
                    @endif
                    @if($user->teacher)
                    <hr>
                    <h6 class="fw-bold"><i class="fa fa-address-card me-1"></i> Informations enseignant</h6>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4"><label class="form-label">CIN</label><input type="text" class="form-control" value="{{ $user->teacher->cin }}" disabled></div>
                        <div class="col-md-4"><label class="form-label">Matière</label><input type="text" class="form-control" value="{{ $user->teacher->specialty ?? '—' }}" disabled></div>
                    </div>
                    @endif
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0"><i class="fa fa-lock me-1"></i> Changer le mot de passe</h6>
            </div>
            <div class="card-body">
                <form action="{{ route(Auth::user()->role . '.profile.password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Min. 8 caractères">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirmer</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-key"></i> Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
