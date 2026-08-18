@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ __('app.admin.stats') }}</h1>

    <div class="row mt-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 h-100">
                <div class="card-body">
                    <h2>{{ \App\Models\Visit::formatCount($analytics['total_views']) }}</h2>
                    <div>{{ __('app.admin.analytics_views') }}</div>
                    <div class="small opacity-75">{{ __('app.admin.analytics_this_month') }} : {{ \App\Models\Visit::formatCount($analytics['views_month']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4 h-100">
                <div class="card-body">
                    <h2>{{ \App\Models\Visit::formatCount($analytics['unique_visitors']) }}</h2>
                    <div>{{ __('app.admin.analytics_unique') }}</div>
                    <div class="small opacity-75">{{ __('app.admin.analytics_pages_per', ['count' => $analytics['pages_per_visitor']]) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-dark text-white mb-4 h-100">
                <div class="card-body">
                    <h2>{{ \App\Models\Visit::formatCount($analytics['unique_month']) }}</h2>
                    <div>{{ __('app.admin.analytics_visitors') }}</div>
                    <div class="small opacity-75">{{ __('app.admin.analytics_this_month') }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning mb-4 h-100">
                <div class="card-body">
                    <h2>{{ number_format($analytics['engagement'], 1, ',', ' ') }}%</h2>
                    <div>{{ __('app.admin.analytics_engagement') }}</div>
                    <div class="small">{{ __('app.admin.analytics_returning', ['count' => $analytics['returning']]) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h2 id="active-visitors-count">{{ $activeVisitors }}</h2>
                    <div>{{ __('app.admin.visits_active') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    {{ __('app.admin.visits_by_region') }}
                </div>
                <div class="card-body">
                    <canvas id="regionChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-line me-1"></i>
                    {{ __('app.admin.visits_by_day') }}
                </div>
                <div class="card-body">
                    <canvas id="visitsChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            {{ __('app.admin.visits_recent') }}
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('app.admin.visits_date') }}</th>
                        <th>{{ __('app.admin.visits_ip') }}</th>
                        <th>{{ __('app.admin.visits_page') }}</th>
                        <th>{{ __('app.admin.visits_region') }}</th>
                        <th>{{ __('app.admin.visits_country') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentVisits as $visit)
                    <tr>
                        <td>{{ $visit->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $visit->ip_address }}</td>
                        <td>{{ $visit->page_url }}</td>
                        <td>{{ $visit->region }}</td>
                        <td>{{ $visit->country }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour le compteur de visiteurs actifs
    function updateActiveVisitors() {
        fetch('/admin/visits/active-visitors')
            .then(response => response.json())
            .then(data => {
                document.getElementById('active-visitors-count').textContent = data.active_visitors;
            })
            .catch(error => console.error('Erreur lors de la récupération des visiteurs actifs:', error));
    }
    
    // Mettre à jour le compteur toutes les 5 secondes
    setInterval(updateActiveVisitors, 5000);
    
    fetch('/admin/visits/chart-data')
        .then(response => response.json())
        .then(data => {
            // Graphique des régions
            const regionCtx = document.getElementById('regionChart');
            new Chart(regionCtx, {
                type: 'pie',
                data: {
                    labels: data.visitsByRegion.map(item => item.label),
                    datasets: [{
                        data: data.visitsByRegion.map(item => item.value),
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                            '#e74a3b', '#858796', '#5a5c69', '#2c9faf'
                        ]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Graphique des visites par jour
            const visitsCtx = document.getElementById('visitsChart');
            new Chart(visitsCtx, {
                type: 'line',
                data: {
                    labels: data.visitsByDay.map(item => item.date),
                    datasets: [{
                        label: @json(__('app.admin.visits_chart_label')),
                        data: data.visitsByDay.map(item => item.count),
                        fill: false,
                        borderColor: '#4e73df',
                        tension: 0.1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
});
</script>
@endpush