<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visit extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'ip_address',
        'location',
        'page_url',
        'user_agent',
        'country',
        'region',
        'city',
    ];

    public static function recordVisit($request)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $pageUrl = $request->fullUrl();

        $location = self::getLocationFromIp($ip);

        return self::create([
            'ip_address' => $ip,
            'location' => $location['location'] ?? null,
            'page_url' => $pageUrl,
            'user_agent' => $userAgent,
            'country' => $location['country'] ?? null,
            'region' => $location['region'] ?? null,
            'city' => $location['city'] ?? null,
        ]);
    }

    protected static function getLocationFromIp($ip)
    {
        $geoService = new \App\Services\IpGeolocationService();

        return $geoService->getLocation($ip);
    }

    public static function getVisitStats()
    {
        return [
            'total' => self::count(),
            'by_region' => self::selectRaw('region, count(*) as count')
                ->whereNotNull('region')
                ->groupBy('region')
                ->get(),
            'by_country' => self::selectRaw('country, count(*) as count')
                ->whereNotNull('country')
                ->groupBy('country')
                ->get(),
            'recent_visits' => self::latest()
                ->take(10)
                ->get(),
            'active_visitors' => self::getActiveVisitors(),
        ];
    }

    public static function getActiveVisitors($minutes = 1)
    {
        $timestamp = now()->subMinutes($minutes);

        return (int) self::where('created_at', '>=', $timestamp)
            ->selectRaw('COUNT(DISTINCT ip_address) as c')
            ->value('c');
    }

    public static function distinctIps($query = null): int
    {
        $query = $query ? clone $query : self::query();

        return (int) $query->selectRaw('COUNT(DISTINCT ip_address) as c')->value('c');
    }

    /**
     * Indicateurs audience pour le dashboard (vues, uniques, engagement).
     *
     * @return array{
     *   total_views:int,
     *   unique_visitors:int,
     *   unique_month:int,
     *   views_month:int,
     *   views_prev_month:int,
     *   views_trend:int,
     *   returning:int,
     *   engagement:float,
     *   pages_per_visitor:float,
     *   active:int
     * }
     */
    public static function overview(): array
    {
        $totalViews = (int) self::count();
        $unique = self::distinctIps();
        $monthStart = now()->startOfMonth();
        $prevStart = now()->subMonth()->startOfMonth();
        $prevEnd = now()->startOfMonth();

        $viewsMonth = (int) self::where('created_at', '>=', $monthStart)->count();
        $viewsPrev = (int) self::whereBetween('created_at', [$prevStart, $prevEnd])->count();
        $uniqueMonth = self::distinctIps(self::where('created_at', '>=', $monthStart));

        $returning = (int) DB::query()
            ->fromSub(
                self::query()->select('ip_address')->groupBy('ip_address')->havingRaw('COUNT(*) >= 2'),
                'returning_ips'
            )
            ->count();

        $engagement = $unique > 0 ? round(($returning / $unique) * 100, 1) : 0.0;
        $pagesPer = $unique > 0 ? round($totalViews / $unique, 1) : 0.0;
        $trend = $viewsPrev > 0
            ? (int) round((($viewsMonth - $viewsPrev) / $viewsPrev) * 100)
            : ($viewsMonth > 0 ? 100 : 0);

        return [
            'total_views' => $totalViews,
            'unique_visitors' => $unique,
            'unique_month' => $uniqueMonth,
            'views_month' => $viewsMonth,
            'views_prev_month' => $viewsPrev,
            'views_trend' => $trend,
            'returning' => $returning,
            'engagement' => $engagement,
            'pages_per_visitor' => $pagesPer,
            'active' => self::getActiveVisitors(5),
        ];
    }

    public static function formatCount(int|float $n): string
    {
        $n = (float) $n;
        if ($n >= 1000000) {
            return rtrim(rtrim(number_format($n / 1000000, 1, ',', ''), '0'), ',').' M';
        }
        if ($n >= 1000) {
            return rtrim(rtrim(number_format($n / 1000, 1, ',', ''), '0'), ',').' k';
        }

        return number_format((int) $n, 0, ',', ' ');
    }
}
