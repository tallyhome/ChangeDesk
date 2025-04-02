<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;

class AdminVisitController extends Controller
{
    public function index()
    {
        $stats = Visit::getVisitStats();
        $totalVisits = $stats['total'];
        $visitsByRegion = $stats['by_region'];
        $visitsByCountry = $stats['by_country'];
        $recentVisits = $stats['recent_visits'];
        $activeVisitors = $stats['active_visitors'];

        return view('admin.visits.index', compact(
            'totalVisits',
            'visitsByRegion',
            'visitsByCountry',
            'recentVisits',
            'activeVisitors'
        ));
    }

    public function getChartData()
    {
        $visitsByRegion = Visit::selectRaw('region, count(*) as count')
            ->whereNotNull('region')
            ->groupBy('region')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->region,
                    'value' => $item->count
                ];
            });

        $visitsByDay = Visit::selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->limit(30)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count
                ];
            });

        return response()->json([
            'visitsByRegion' => $visitsByRegion,
            'visitsByDay' => $visitsByDay
        ]);
    }
    
    /**
     * Récupère le nombre de visiteurs actifs en temps réel
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function getActiveVisitors()
    {
        $activeVisitors = Visit::getActiveVisitors();
        return response()->json(['active_visitors' => $activeVisitors]);
    }
}