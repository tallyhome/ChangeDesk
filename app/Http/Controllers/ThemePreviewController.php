<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemePreviewController extends Controller
{
    public function __invoke(string $theme): View
    {
        $theme = strtolower($theme);
        abort_unless(in_array($theme, ['classic', 'midnight', 'editorial', 'aurora'], true), 404);

        return view('central.theme-previews.'.$theme, [
            'demoName' => 'Acme Product',
            'versions' => [
                ['number' => '2.4.0', 'date' => '12 août 2026', 'title' => 'Thèmes & facturation', 'body' => 'Nouveaux thèmes publics, plans Pro/Business et domaine custom.'],
                ['number' => '2.3.0', 'date' => '28 juil. 2026', 'title' => 'Multi-tenant', 'body' => 'Sous-domaines, isolation des données et espace superadmin.'],
                ['number' => '2.1.0', 'date' => '10 juin 2026', 'title' => 'Wiki & bugs', 'body' => 'Base de connaissances et rapports de bugs publics.'],
            ],
        ]);
    }
}
