<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                {{ __('app.admin.dashboard') }}
            </a>

            <div class="sb-sidenav-menu-heading">{{ __('app.admin.stats') }}</div>
            <a class="nav-link" href="{{ route('admin.visits.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                {{ __('app.admin.stats') }}
            </a>
            <a class="nav-link" href="{{ route('admin.visits.analysis') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                {{ __('app.admin.stats') }}
            </a>

            <div class="sb-sidenav-menu-heading">{{ __('app.admin.wiki') }}</div>
            <a class="nav-link" href="{{ route('admin.wiki.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                {{ __('app.common.articles') }}
            </a>
            <a class="nav-link" href="{{ route('admin.wiki.categories.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-folder"></i></div>
                {{ __('app.common.categories') }}
            </a>

            <div class="sb-sidenav-menu-heading">{{ __('app.admin.pages') }}</div>
            <a class="nav-link" href="{{ route('admin.pages.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                {{ __('app.admin.pages') }}
            </a>
            <a class="nav-link" href="{{ route('admin.changelog') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                {{ __('app.admin.changelog') }}
            </a>

            <div class="sb-sidenav-menu-heading">{{ __('app.admin.administration') }}</div>
            <a class="nav-link" href="{{ route('admin.bug_reports') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-bug"></i></div>
                {{ __('app.admin.bug_reports') }}
            </a>
            <a class="nav-link" href="{{ route('admin.todolist') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                {{ __('app.admin.upcoming') }}
            </a>

            <div class="sb-sidenav-menu-heading">{{ __('app.admin.settings') }}</div>
            <a class="nav-link" href="{{ route('admin.domain.edit') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-globe"></i></div>
                {{ __('app.admin.domain') }}
            </a>
            <a class="nav-link" href="{{ route('admin.appearance.edit') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-palette"></i></div>
                {{ __('app.admin.appearance') }}
            </a>
            <a class="nav-link" href="{{ route('admin.billing.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-credit-card"></i></div>
                {{ __('app.admin.billing') }}
            </a>
            <a class="nav-link" href="{{ route('admin.settings.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                {{ __('app.admin.settings') }}
            </a>
            <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                {{ __('app.admin.profile') }}
            </a>
        </div>
    </div>
</nav>
