<?php

namespace App\Http\Controllers;

use App\Models\Tenant;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (Tenant::current()) {
            return app(PageController::class)->index();
        }

        return view('central.landing');
    }
}
