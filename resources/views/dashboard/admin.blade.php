@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-header', 'Tableau de Bord')
@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h6 class="fw-bold mb-2"><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fff;border:2px solid #dee2e6;vertical-align:middle;"><img src="{{ asset('logo-ecocle.svg') }}" alt="Logo" width="20" height="20" style="object-fit:contain;"></span> Guide pour comprendre - SoufianeDevops</h6>
        <p class="text-muted small mb-2">1) Aller vers Modules, creer un module et le lier a une classe (CP1, GI1 ...), cest mieux de tester tous ca pour comprendre lapp </p>
            <p class="text-muted small mb-2">2) Ajouter un professeur en allant vers Enseignants,</p>
            <p class="text-muted small mb-2">3) Creer un etudiant de test en allant vers Etudiants,</p>
            <p class="text-muted small mb-2">4) Creer un compte pour le professeur et pour etudiant aussi, ca en allant vers comptes ;  NB (memoriser le user et mdp) pour avoir acces a l'application pour tester,</p>
            <p class="text-muted small mb-2">5) Affecter un module a un professeur en allant vers Affectations,</p>
            <p class="text-muted small mb-2">6) Aller vers Administrateur et logout,  </p>
            <p class="text-muted small mb-2">7) Connecter le compte du prof cree , nb:Si tu as oublie le user w mot de passe, entrer par admin/admin aller vers Comptes et recuperer user/mdpss ; apres lentre comme un prof annoncer qu'il y a une seance en allant vers Mes Seances, il faut avoir que l'etudiant est un etudiant chez le prof, en faisant l'action APPEL on voit tous les etudiants du module du prof et c'est ca le but le prof peut enregistrer l'absence et en connectant avec le compte de l'etudiant on voit une notification quelque soit de retard ou d'absence ....</p>
            <p class="text-muted small mb-0">8) Il y a aussi beaucoup de choses en connectant vers le compte etudiant par logout ,  tu peux tester le logic : envoyer un justification par un login etudiant et par un login prof on voit la reception de ca </p>
        <div class="mt-2 pt-2 border-top small text-muted">
            <i class="fa fa-key me-1"></i> Connexion admin : <code>admin</code> / <code>admin</code>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-3 ms-auto">
        <form method="GET" class="d-flex">
            <select name="semester_id" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les semestres</option>
                @foreach($semesters as $id=>$name)
                <option value="{{ $id }}" {{ request('semester_id')==$id ? 'selected':'' }}>{{ $name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3"><x-stat-card title="Utilisateurs" :value="$stats['total_users']" icon="fa-users" color="primary" /></div>
    <div class="col-md-3"><x-stat-card title="Enseignants" :value="$stats['total_teachers']" icon="fa-address-card" color="info" /></div>
    <div class="col-md-3"><x-stat-card title="Étudiants" :value="$stats['total_students']" icon="fa-user-graduate" color="success" /></div>
    <div class="col-md-3"><x-stat-card title="Filières" :value="$stats['total_filieres']" icon="fa-sitemap" color="warning" /></div>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3"><x-stat-card title="Classes" :value="$stats['total_classes']" icon="fa-building" color="info" /></div>
    <div class="col-md-3"><x-stat-card title="Modules" :value="$stats['total_modules']" icon="fa-book" color="primary" /></div>
    <div class="col-md-3"><x-stat-card title="Séances" :value="$stats['total_seances']" icon="fa-calendar" color="secondary" /></div>
    <div class="col-md-3"><x-stat-card title="Absences" :value="$stats['total_absences']" icon="fa-user-times" color="danger" /></div>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="card-title fw-bold mb-3">Statut des Comptes</h6>
                <div class="d-flex justify-content-between mb-2"><span>Comptes actifs</span><span class="badge bg-success">{{ $stats['active_users'] }}</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Comptes inactifs</span><span class="badge bg-danger">{{ $stats['total_users'] - $stats['active_users'] }}</span></div>
                @php $pct = $stats['total_users'] > 0 ? ($stats['active_users'] / $stats['total_users']) * 100 : 0; @endphp
                <div class="progress mt-2" style="height:6px"><div class="progress-bar bg-success" style="width:{{ $pct }}%"></div></div>
                <hr>
                <h6 class="fw-bold mb-3">Absences non justifiées</h6>
                <div class="d-flex justify-content-between mb-2"><span>Total</span><span class="badge bg-danger">{{ $stats['unjustified_absences'] }}</span></div>
                <div class="d-flex justify-content-between"><span>Justifications en attente</span><span class="badge bg-warning">{{ $stats['pending_justifications'] }}</span></div>
                @if($stats['selected_semester'])
                <div class="mt-2"><small class="text-muted">Filtré par : {{ $stats['selected_semester']->name }}</small></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Activité Récente</h6>
                <a href="{{ route('admin.activity-logs') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($stats['recent_logs'] as $log)
                    <div class="list-group-item py-3">
                        <div class="d-flex justify-content-between">
                            <div><strong>{{ $log->user?->name ?? 'Système' }}</strong> <span class="text-muted ms-1">{{ $log->description }}</span></div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">Aucune activité enregistrée.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
