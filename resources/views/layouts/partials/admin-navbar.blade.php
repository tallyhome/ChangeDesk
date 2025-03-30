<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index: 1030; height: 56px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">MyVcard MyPredict - Administration <span class="badge bg-danger">v{{ $appVersion }}</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <button class="nav-link" onclick="toggleTheme()" style="background: none; border: none; cursor: pointer;">
                        <i id="theme-icon" class="fas fa-moon"></i>
                    </button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-user-cog"></i> Mon Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Voir le site
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>