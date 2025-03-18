<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Version;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->first();
        return view('pages.home', compact('page'));
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms')->first();
        return view('pages.terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->first();
        return view('pages.privacy', compact('page'));
    }

    public function changelog()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('pages.changelog', compact('versions'));
    }
}