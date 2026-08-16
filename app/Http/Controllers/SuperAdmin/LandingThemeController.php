<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Support\LandingTheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingThemeController extends Controller
{
    public function index(): View
    {
        return view('superadmin.landing.index', [
            'themes' => LandingTheme::all(),
            'current' => LandingTheme::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'theme' => ['required', 'string', 'in:'.implode(',', LandingTheme::slugs())],
        ]);

        LandingTheme::set($data['theme']);

        return redirect()
            ->route('superadmin.landing.index')
            ->with('success', 'Thème vitrine « '.LandingTheme::label($data['theme']).' » activé.');
    }

    public function preview(string $theme): View
    {
        abort_unless(LandingTheme::isValid($theme), 404);

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view(LandingTheme::view($theme), compact('plans'));
    }
}
