<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <span class="nav-link">Pages</span>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.pages.index') }}">Pages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.changelog') }}">Changelog</a>
                </li>
            </ul>
        </li>
    </ul>
</div>

<style>
.sidebar {
    padding: 20px 0;
}
.sidebar .nav-link {
    color: #333;
    padding: 0.5rem 1rem;
}
.sidebar .nav-link:hover {
    color: #0d6efd;
}
</style>