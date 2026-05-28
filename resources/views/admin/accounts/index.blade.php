@extends('layouts.admin')
@section('title', 'Gestion des Comptes')
@section('page-header', 'Comptes Utilisateurs')
@section('page-actions')
    <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouveau Compte</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Nom, Email, Identifiant..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les rôles</option>
                    <option value="teacher" {{ request('role')=='teacher'?'selected':''}}>Enseignant</option>
                    <option value="student" {{ request('role')=='student'?'selected':''}}>Étudiant</option>
                </select>
            </div>
        </form>
        <div class="alert alert-info mb-3">
            <i class="fa fa-info-circle"></i>
            Verrouille car par exp un etudiant faire des tentatives a entrer vers un compte dun autre etudiant ou prof , ladmin va observer une remarque pour ce compte dans historique  connexions et va bloquer ou verouille ce compte
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Identifiant</th>
                        <th>Mot de passe</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Verrouillé</th>
                        <th>Dernière connexion</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="fw-medium">{{ $user->name }}</td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>
                            @if($user->plain_password)
                                <code>{{ $user->plain_password }}</code>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td><small>{{ $user->email }}</small></td>
                        <td>
                            @if($user->role == 'teacher')
                                <span class="badge bg-info">Enseignant</span>
                            @elseif($user->role == 'student')
                                <span class="badge bg-primary">Étudiant</span>
                            @else
                                <span class="badge bg-secondary">{{ $user->role }}</span>
                            @endif
                        </td>
                        <td>{!! $user->is_active ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' !!}</td>
                        <td>{!! $user->isLocked() ? '<span class="badge bg-warning">Verrouillé</span>' : '<span class="badge bg-secondary">Non</span>' !!}</td>
                        <td><small>{{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Jamais' }}</small></td>
                        <td class="text-end">
                            <form action="{{ route('admin.accounts.toggle-active', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fa fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.accounts.toggle-lock', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-{{ $user->isLocked() ? 'success' : 'warning' }}" title="{{ $user->isLocked() ? 'Déverrouiller' : 'Verrouiller' }}">
                                    <i class="fa fa-{{ $user->isLocked() ? 'unlock' : 'lock' }}"></i>
                                </button>
                            </form>
                            <a href="{{ route('admin.accounts.reset-password.form', $user) }}" class="btn btn-sm btn-outline-info" title="Réinitialiser MDP">
                                <i class="fa fa-key"></i>
                            </a>
                            <form action="{{ route('admin.accounts.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce compte ? Cette action est irreversible.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4">Aucun compte trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
