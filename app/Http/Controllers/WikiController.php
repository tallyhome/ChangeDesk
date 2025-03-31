<?php

namespace App\Http\Controllers;

use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WikiController extends Controller
{
    public function __construct()
    {
        $wikiEnabled = \App\Models\Setting::where('key', 'wiki_enabled')->value('value') ?? true;
        if (!$wikiEnabled) {
            abort(404, 'Le wiki est actuellement désactivé.');
        }
    }

    /**
     * Affiche la page d'accueil du wiki
     */
    public function index()
    {
        $categories = WikiCategory::orderBy('order')->get();
        $recentArticles = WikiArticle::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        return view('pages.wiki.index', compact('categories', 'recentArticles'));
    }

    /**
     * Affiche tous les articles d'une catégorie
     */
    public function category($slug)
    {
        $category = WikiCategory::where('slug', $slug)->firstOrFail();
        $categories = WikiCategory::orderBy('order')->get();
        $articles = $category->publishedArticles;
        
        return view('pages.wiki.category', compact('category', 'categories', 'articles'));
    }

    /**
     * Affiche un article spécifique
     */
    public function show($slug)
    {
        $article = WikiArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $categories = WikiCategory::orderBy('order')->get();
        $relatedArticles = $article->relatedArticles;
        
        return view('pages.wiki.show', compact('article', 'categories', 'relatedArticles'));
    }

    /**
     * Recherche dans le wiki
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $categories = WikiCategory::orderBy('order')->get();
        
        $articles = WikiArticle::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->get();
            
        return view('pages.wiki.search', compact('articles', 'categories', 'query'));
    }
}