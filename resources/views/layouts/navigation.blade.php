<nav class="navbar navbar-expand-md navbar-light bg-primary">
    <div class="container">
        <a class="navbar-brand text-white" href="{{ url('/') }}">MyVcard MyPredict ChanLog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('changelog') }}">Changelog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('terms') }}">Conditions d'utilisation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('privacy') }}">Politique de confidentialité</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown">
                            Pages
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.pages.index') }}">Pages</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.changelog') }}">Changelog</a></li>
                        </ul>
                    </li>
                @endauth
            </ul>
            
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Connexion</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('register') }}">Inscription</a>
                    </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a></li>
                        </ul>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>