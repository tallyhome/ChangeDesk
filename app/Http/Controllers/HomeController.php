<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (Tenant::current()) {
            return app(PageController::class)->index();
        }

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('central.landing', compact('plans'));
    }
}
