<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        return self::where('created_at', '>=', $timestamp)
            ->distinct('ip_address')
            ->count('ip_address');
    }
}
