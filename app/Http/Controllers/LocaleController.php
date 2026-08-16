<?php

namespace App\Http\Controllers;

use App\Support\Locale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        abort_unless(Locale::isSupported($locale), 404);

        $request->session()->put('locale', $locale);

        $redirect = url()->previous();
        if (! $redirect || $redirect === url()->current()) {
            $redirect = url('/');
        }

        return redirect($redirect)
            ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
