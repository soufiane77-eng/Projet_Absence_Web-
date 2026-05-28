<nav class="top-navbar">
    <div class="d-flex align-items-center">
        <button class="btn btn-link d-md-none text-dark me-2" onclick="toggleSidebar()">
            <i class="fa fa-bars fa-lg"></i>
        </button>
        <form class="d-none d-sm-block" method="GET" action="#">
            <div class="input-group input-group-sm" style="max-width: 300px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Search...">
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
            <button class="btn btn-link text-dark position-relative p-0" data-bs-toggle="dropdown">
                <i class="fa fa-bell fa-lg"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">0</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 280px;">
                <div class="dropdown-header fw-bold">Notifications</div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-muted small text-center" href="#">No new notifications</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-link text-dark dropdown-toggle text-decoration-none p-0 d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                     style="width: 35px; height: 35px; border:2px solid #dee2e6;">
                    <img src="{{ asset('logo-ecocle.svg') }}" alt="Logo" width="22" height="22" style="object-fit:contain;">
                </div>
                @php
                    $roleLabel = match(auth()->user()->role) {
                        'admin' => 'Administrateur',
                        'teacher' => 'Enseignant',
                        'student' => 'Étudiant',
                        default => ucfirst(auth()->user()->role),
                    };
                @endphp
                <span class="d-none d-md-inline small">
                    {{ auth()->user()->name }}
                    <span class="badge badge-role-{{ auth()->user()->role }} ms-1">{{ $roleLabel }}</span>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                @if(Auth::user()->role === 'admin')
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa fa-user-circle me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="fa fa-cog me-2"></i>Paramètres</a></li>
                <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="fa fa-sign-out me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
