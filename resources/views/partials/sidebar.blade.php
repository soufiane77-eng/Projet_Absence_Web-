<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fa fa-graduation-cap me-2"></i>
        <span>E-NSAH Hoceima</span>
    </div>
    <ul class="nav flex-column mt-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}"
               href="{{ match(auth()->user()->role) {
                   'admin' => route('admin.dashboard'),
                   'teacher' => route('teacher.dashboard'),
                   'student' => route('student.dashboard'),
               } }}">
                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:#fff;"><img src="{{ asset('logo-ecocle.svg') }}" alt="Logo" width="18" height="18" style="object-fit:contain;"></span> <span>Tableau de Bord</span>
            </a>
        </li>
        @role('admin')
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Structure Pédagogique</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.filieres*') ? 'active' : '' }}" href="{{ route('admin.filieres.index') }}">
                <i class="fa fa-sitemap"></i> <span>Filières</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
                <i class="fa fa-building"></i> <span>Classes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.modules*') ? 'active' : '' }}" href="{{ route('admin.modules.index') }}">
                <i class="fa fa-book"></i> <span>Modules</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.elements*') ? 'active' : '' }}" href="{{ route('admin.elements.index') }}">
                <i class="fa fa-list"></i> <span>Éléments</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.semesters*') ? 'active' : '' }}" href="{{ route('admin.semesters.index') }}">
                <i class="fa fa-calendar-check-o"></i> <span>Semestres</span>
            </a>
        </li>
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Ressources Humaines</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.teachers*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                <i class="fa fa-address-card"></i> <span>Enseignants</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                <i class="fa fa-users"></i> <span>Étudiants</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.assignments*') ? 'active' : '' }}" href="{{ route('admin.assignments.index') }}">
                <i class="fa fa-tasks"></i> <span>Affectations</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}" href="{{ route('admin.accounts.index') }}">
                <i class="fa fa-user-circle"></i> <span>Comptes</span>
            </a>
        </li>
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Gestion des Absences</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.seances*') ? 'active' : '' }}" href="{{ route('admin.seances.index') }}">
                <i class="fa fa-calendar"></i> <span>Séances</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.absences*') && !request()->routeIs('admin.absences.justifications*') ? 'active' : '' }}" href="{{ route('admin.absences.index') }}">
                <i class="fa fa-user-times"></i> <span>Absences</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.absences.justifications*') ? 'active' : '' }}" href="{{ route('admin.absences.justifications') }}">
                <i class="fa fa-file-text"></i> <span>Justifications</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reclamations*') ? 'active' : '' }}" href="{{ route('admin.reclamations.index') }}">
                <i class="fa fa-exclamation-circle"></i> <span>Réclamations</span>
            </a>
        </li>
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Système</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}" href="{{ route('admin.activity-logs') }}">
                <i class="fa fa-history"></i> <span>Journal d'Activité</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.login-history*') ? 'active' : '' }}" href="{{ route('admin.login-history') }}">
                <i class="fa fa-sign-in"></i> <span>Historique Connexions</span>
            </a>
        </li>
        @endrole
        @role('teacher')
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Enseignement</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.seances*') ? 'active' : '' }}" href="{{ route('teacher.seances.index') }}">
                <i class="fa fa-chalkboard"></i> <span>Mes Séances</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.absences*') && !request()->routeIs('teacher.justifications*') ? 'active' : '' }}" href="{{ route('teacher.absences.index') }}">
                <i class="fa fa-user-times"></i> <span>Absences</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('teacher.justifications*') ? 'active' : '' }}" href="{{ route('teacher.justifications.index') }}">
                <i class="fa fa-file-text"></i> <span>Justifications</span>
            </a>
        </li>
        @endrole
        @role('student')
        <li class="nav-item mt-2"><small class="text-muted px-3 text-uppercase">Mon Portail</small></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.absences*') ? 'active' : '' }}" href="{{ route('student.absences.index') }}">
                <i class="fa fa-user-times"></i> <span>Mes Absences</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.justifications*') ? 'active' : '' }}" href="{{ route('student.justifications.index') }}">
                <i class="fa fa-upload"></i> <span>Justifications</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('student.reclamations*') ? 'active' : '' }}" href="{{ route('student.reclamations.index') }}">
                <i class="fa fa-exclamation-triangle"></i> <span>Réclamations</span>
            </a>
        </li>
        @endrole
    </ul>
</aside>
