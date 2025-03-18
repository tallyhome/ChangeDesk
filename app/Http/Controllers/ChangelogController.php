<?php

namespace App\Http\Controllers;

use App\Models\Page;

class ChangelogController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'changelog')->firstOrFail();
        return view('pages.changelog', compact('page'));
    }

    public function adminIndex()
    {
        $page = Page::where('slug', 'changelog')->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }
}