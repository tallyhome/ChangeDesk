<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class IpGeolocationService
{
    protected $baseUrl = 'http://ip-api.com/json/';

    public function getLocation($ip)
    {
        // Pour les adresses IP locales, retourner des données fictives pour permettre le tracking en local
        if ($ip == '127.0.0.1' || $ip == 'localhost' || $ip == '::1') {
            $location = [
                'location' => 'Local',
                'country' => 'Local',
                'region' => 'Développement',
                'city' => 'Localhost'
            ];
            return $location;
        }
        
        // Vérifie si l'IP est déjà en cache
        $cacheKey = 'ip_location_' . $ip;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::get($this->baseUrl . $ip);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'success') {
                    $location = [
                        'location' => $data['country'],
                        'country' => $data['country'],
                        'region' => $data['regionName'],
                        'city' => $data['city']
                    ];

                    // Cache les résultats pendant 24 heures
                    Cache::put($cacheKey, $location, now()->addHours(24));

                    return $location;
                }
            }

            return [
                'location' => null,
                'country' => null,
                'region' => null,
                'city' => null
            ];
        } catch (\Exception $e) {
            report($e);
            return [
                'location' => null,
                'country' => null,
                'region' => null,
                'city' => null
            ];
        }
    }
}