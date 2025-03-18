<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pages = Page::select('id', 'title', 'updated_at', 'created_at')->get();
        return view('admin.dashboard', compact('pages'));
    }

    public function index()
    {
        // Modifions pour inclure toutes les pages, y compris le changelog
        $pages = Page::select('id', 'title', 'slug')
                 ->orderBy('title')
                 ->get();

        \Log::info("Pages récupérées:", [
            'count' => $pages->count(),
            'titles' => $pages->pluck('title')->toArray()
        ]);

        return view('admin.pages.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = Page::select('id', 'title', 'content')->findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->update($request->all());
        return redirect()->route('admin.pages.index')->with('success', 'Page mise à jour avec succès');
    }

    public function changelog()
    {
        $page = Page::select('id', 'title', 'content', 'slug')
                ->where('slug', 'changelog')
                ->orWhere('title', 'Changelog')
                ->first();

        \Log::info("Admin changelog - Données récupérées:", [
            'exists' => $page ? 'oui' : 'non',
            'title' => $page ? $page->title : 'non trouvé',
            'slug' => $page ? $page->slug : 'non trouvé'
        ]);

        return view('admin.changelog', compact('page'));
    }
}