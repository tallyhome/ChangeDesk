<?php

namespace App\Support;

use App\Models\Tenant;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

class ThemeView
{
    public static function make(string $page, array $data = []): ViewContract
    {
        $theme = Tenant::current()?->theme() ?? 'classic';
        $data = $data + ['theme' => $theme];

        $candidate = "themes.{$theme}.pages.{$page}";
        if (View::exists($candidate)) {
            return view($candidate, $data);
        }

        // Fallback classic themed page (si présent)
        $classic = "themes.classic.pages.{$page}";
        if ($theme !== 'classic' && View::exists($classic)) {
            return view($classic, $data);
        }

        // Pages legacy : doivent @extends(ThemeView::layout())
        if (View::exists("pages.{$page}")) {
            return view("pages.{$page}", $data);
        }

        return view($classic, $data);
    }

    public static function layout(): string
    {
        $theme = Tenant::current()?->theme() ?? 'classic';
        $layout = "themes.{$theme}.layouts.app";

        return View::exists($layout) ? $layout : 'themes.classic.layouts.app';
    }
}
