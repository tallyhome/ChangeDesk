<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Version;
use Illuminate\Support\Str;

class FeedController extends Controller
{
    public function rss()
    {
        $tenant = Tenant::current();
        abort_unless($tenant, 404);

        $versions = Version::query()->orderByDesc('release_date')->limit(30)->get();

        return response()
            ->view('feeds.changelog', compact('tenant', 'versions'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    public function widgetFeed()
    {
        $tenant = Tenant::current();
        abort_unless($tenant, 404);

        $base = $tenant->publicBaseUrl();
        $versions = Version::query()->orderByDesc('release_date')->limit(5)->get()->map(function (Version $version) use ($base) {
            $plain = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $version->content))) ?? '');

            return [
                'version' => (string) $version->version_number,
                'date' => optional($version->release_date)->toDateString(),
                'excerpt' => Str::limit($plain, 180, ''),
                'url' => $base.'/changelog#version-'.$version->id,
            ];
        })->values();

        return response()->json([
            'name' => $tenant->name,
            'changelog' => $base.'/changelog',
            'versions' => $versions,
        ], 200, [
            'Access-Control-Allow-Origin' => '*',
            'Cache-Control' => 'public, max-age=120',
        ]);
    }
}
