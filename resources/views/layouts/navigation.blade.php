@php
    $siteBrand = $currentTenant->name ?? config('app.name');
    $centralBase = rtrim(config('app.url'), '/');
    $loginUrl = $centralBase.'/login';
    $adminUrl = $centralBase.'/admin';
@endphp
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0d9488">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">{{ $siteBrand }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Accueil</a>
                </li>
                @php
                    $changelogEnabled = \App\Models\Setting::getValue('changelog_enabled', true);
                    $todoEnabled = \App\Models\Setting::getValue('todo_enabled', true);
                    $bugReportEnabled = \App\Models\Setting::getValue('bug_report_enabled', true);
                    $wikiEnabled = \App\Models\Setting::getValue('wiki_enabled', true);
                    $externalUrl = \App\Models\Setting::getValue('external_link_url');
                    $externalText = \App\Models\Setting::getValue('external_link_text', 'Lien externe');
                    $externalEnabled = \App\Models\Setting::getValue('external_link_enabled', '0');
                    $gate = app(\App\Services\PlanGate::class);
                    $t = $currentTenant ?? null;
                @endphp

                @if($changelogEnabled)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('changelog') ? 'active' : '' }}" href="{{ route('changelog') }}">Changelog</a>
                </li>
                @endif

                @if($todoEnabled && $gate->can($t, 'todolist'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('todolist') ? 'active' : '' }}" href="{{ route('todolist') }}">Prochaines fonctionnalités</a>
                </li>
                @endif

                @if($bugReportEnabled && $gate->can($t, 'bugs'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bug-report*') ? 'active' : '' }}" href="{{ route('bug-report') }}">Signaler un bug</a>
                </li>
                @endif

                @if($wikiEnabled && $gate->can($t, 'wiki'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('wiki*') ? 'active' : '' }}" href="{{ route('wiki') }}">Wiki</a>
                </li>
                @endif

                @if($externalUrl && $externalEnabled == '1')
                <li class="nav-item">
                    <a class="nav-link" href="{{ $externalUrl }}" target="_blank" rel="noopener">{{ $externalText }}</a>
                </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ $adminUrl }}" class="btn btn-light btn-sm">Administration</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ $loginUrl }}" class="btn btn-light btn-sm">Connexion</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
