<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.wiki.index') }}">
            <i class="fas fa-book me-1"></i> Wiki
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.visits.*') ? 'active' : '' }}" href="{{ route('admin.visits.index') }}">
            <i class="fas fa-chart-line me-1"></i> Statistiques
        </a>
    </li>
</ul>