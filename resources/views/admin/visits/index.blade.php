@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Statistiques des visites</h1>

    <div class="row mt-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h2>{{ number_format($totalVisits) }}</h2>
                    <div>Visites totales</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h2 id="active-visitors-count">{{ $activeVisitors }}</h2>
                    <div>Visiteurs actifs en temps réel</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Répartition par région
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
                    Visites par jour (30 derniers jours)
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
            Dernières visites
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>IP</th>
                        <th>Page</th>
                        <th>Région</th>
                        <th>Pays</th>
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
                        label: 'Nombre de visites',
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