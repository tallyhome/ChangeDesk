<!-- Ajoutez ce code à votre fichier de navigation -->

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                </li>
                
                @php
                    $changelogEnabled = \App\Models\Setting::getValue('changelog_enabled', true);
                    $todoEnabled = \App\Models\Setting::getValue('todo_enabled', true);
                    $bugReportEnabled = \App\Models\Setting::getValue('bug_report_enabled', true);
                    $wikiEnabled = \App\Models\Setting::getValue('wiki_enabled', true);
                    $externalUrl = \App\Models\Setting::getValue('external_link_url');
                    $externalText = \App\Models\Setting::getValue('external_link_text', 'Lien externe');
                    $externalActive = \App\Models\Setting::getValue('external_link_active', '1');
                @endphp
                
                @if($changelogEnabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('changelog') ? 'active' : '' }}" href="{{ route('changelog') }}">
                        <i class="fas fa-history me-1"></i>Changelog
                    </a>
                </li>
                @endif
                
                @if($todoEnabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('todolist') ? 'active' : '' }}" href="{{ route('todolist') }}">
                        <i class="fas fa-tasks me-1"></i>Prochaines fonctionnalités
                    </a>
                </li>
                @endif
                
                @if($bugReportEnabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bug-report.*') ? 'active' : '' }}" href="{{ route('bug-report') }}">
                        <i class="fas fa-bug me-1"></i>Signaler un bug
                    </a>
                </li>
                @endif
                
                @if($wikiEnabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('wiki.*') ? 'active' : '' }}" href="{{ route('wiki') }}">
                        <i class="fas fa-book me-1"></i>Wiki
                    </a>
                </li>
                @endif
                
                @if($externalUrl && $externalActive == '1')
                <li class="nav-item">
                    <a class="nav-link" href="{{ $externalUrl }}" target="_blank">{{ $externalText }}</a>
                </li>
                @endif
            </ul>
            
            <!-- Bouton Jour/Nuit -->
            <div class="d-flex align-items-center gap-3">
                <style>
                    #theme-icon {
                        font-size: 1.2rem;
                        color: var(--nav-text) !important;
                        transition: all 0.3s ease;
                        display: inline-block;
                        text-shadow: 0 0 5px rgba(255, 255, 255, 0.2);
                    }
                    #theme-icon:hover {
                        transform: rotate(360deg);
                        color: var(--nav-text) !important;
                        text-shadow: 0 0 8px rgba(255, 255, 255, 0.4);
                    }
                </style>
                <button class="nav-link" onclick="toggleTheme()" style="background: none; border: none; cursor: pointer;">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>
                <!-- Menu utilisateur -->
                @auth
                    <div class="dropdown ms-3">
                        <button class="btn btn-light dropdown-toggle w-100" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-cogs me-2"></i>Administration</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light w-100"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a>
                @endauth
            </div>
        </div>
    </div>
</nav>