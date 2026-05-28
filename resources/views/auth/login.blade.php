@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <h4 class="text-center mb-4">Connexion</h4>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control @error('username') is-invalid @enderror"
                       id="username" name="username" value="{{ old('username') }}" required autofocus autocomplete="username">
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" required autocomplete="current-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        @if($showCaptcha)
        <div class="mb-3">
            <label for="captcha" class="form-label">Vérification de sécurité</label>
            <div class="mb-2">{!! captcha_img() !!}</div>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-shield"></i></span>
                <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                       id="captcha" name="captcha" placeholder="Saisissez le code ci-dessus" required>
                @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-decoration-none small">Mot de passe oublié ?</a>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill py-2">
                <i class="fa fa-sign-in me-1"></i> Se connecter
            </button>
            <button type="button" class="btn btn-outline-info py-2" data-bs-toggle="offcanvas" data-bs-target="#loginGuide" aria-controls="loginGuide">
                <i class="fa fa-book me-1"></i> Afficher le guide
            </button>
        </div>
    </form>
@endsection

@section('extra')
    <div class="offcanvas offcanvas-end" tabindex="-1" id="loginGuide" aria-labelledby="loginGuideLabel">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title fw-bold" id="loginGuideLabel">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fff;border:2px solid #dee2e6;vertical-align:middle;">
                    <img src="{{ asset('logo-ecocle.svg') }}" alt="Logo" width="20" height="20" style="object-fit:contain;">
                </span>
                Guide pour comprendre - SoufianeDevops
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p class="text-muted small mb-2">1) Aller vers Modules, creer un module et le lier a une classe (CP1, GI1 ...), cest mieux de tester tous ca pour comprendre lapp </p>
            <p class="text-muted small mb-2">2) Ajouter un professeur en allant vers Enseignants,</p>
            <p class="text-muted small mb-2">3) Creer un etudiant de test en allant vers Etudiants,</p>
            <p class="text-muted small mb-2">4) Creer un compte pour le professeur et pour etudiant aussi, ca en allant vers comptes ;  NB (memoriser le user et mdp) pour avoir acces a l'application pour tester,</p>
            <p class="text-muted small mb-2">5) Affecter un module a un professeur en allant vers Affectations,</p>
            <p class="text-muted small mb-2">6) Aller vers Administrateur et logout,  </p>
            <p class="text-muted small mb-2">7) Connecter le compte du prof cree , nb:Si tu as oublie le user w mot de passe, entrer par admin/admin aller vers Comptes et recuperer user/mdpss ; apres lentre comme un prof annoncer qu'il y a une seance en allant vers Mes Seances, il faut avoir que l'etudiant est un etudiant chez le prof, en faisant l'action APPEL on voit tous les etudiants du module du prof et c'est ca le but le prof peut enregistrer l'absence et en connectant avec le compte de l'etudiant on voit une notification quelque soit de retard ou d'absence ....</p>
            <p class="text-muted small mb-0">8) Il y a aussi beaucoup de choses en connectant vers le compte etudiant par logout ,  tu peux tester le logic : envoyer un justification par un login etudiant et par un login prof on voit la reception de ca </p>
            <div class="mt-3 pt-3 border-top small text-muted">
                <i class="fa fa-key me-1"></i> Connexion admin : <code>admin</code> / <code>admin</code>
            </div>
        </div>
    </div>
@endsection
