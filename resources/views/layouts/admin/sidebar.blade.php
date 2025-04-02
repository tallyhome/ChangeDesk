<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Tableau de bord
            </a>

            <div class="sb-sidenav-menu-heading">Statistiques</div>
            <a class="nav-link" href="{{ route('admin.visits.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                Statistiques des visites
            </a>
            <a class="nav-link" href="{{ route('admin.visits.analysis') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                Analyse des visites
            </a>

            <div class="sb-sidenav-menu-heading">Wiki</div>
            <a class="nav-link" href="{{ route('admin.wiki.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                Articles
            </a>
            <a class="nav-link" href="{{ route('admin.wiki.categories.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-folder"></i></div>
                Catégories
            </a>

            <div class="sb-sidenav-menu-heading">Contenu</div>
            <a class="nav-link" href="{{ route('admin.pages.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                Pages
            </a>
            <a class="nav-link" href="{{ route('admin.changelog') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                Changelog
            </a>

            <div class="sb-sidenav-menu-heading">Gestion</div>
            <a class="nav-link" href="{{ route('admin.bug-reports.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-bug"></i></div>
                Rapports de bugs
            </a>
            <a class="nav-link" href="{{ route('admin.todo-items.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                Todo List
            </a>

            <div class="sb-sidenav-menu-heading">Configuration</div>
            <a class="nav-link" href="{{ route('admin.settings.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                Paramètres
            </a>
            <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                Profil
            </a>
        </div>
    </div>
</nav>