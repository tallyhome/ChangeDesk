<?php

namespace App\Http\Controllers;

use App\Models\WikiArticle;
use App\Models\WikiCategory;
use App\Support\ThemeView;
use Illuminate\Http\Request;

class WikiController extends Controller
{
    public function __construct()
    {
        $wikiEnabled = \App\Models\Setting::where('key', 'wiki_enabled')->value('value') ?? true;
        if (! $wikiEnabled) {
            abort(404, 'Le wiki est actuellement désactivé.');
        }
    }

    public function index()
    {
        $categories = WikiCategory::orderBy('order')->get();
        $recentArticles = WikiArticle::where('is_published', true)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $wikiTitle = \App\Models\Setting::getValue('wiki_title', 'Wiki');
        $welcomeTitle = \App\Models\Setting::getValue('wiki_welcome_title', 'Bienvenue dans le Wiki');
        $welcomeText = \App\Models\Setting::getValue('wiki_welcome_text', '');

        return ThemeView::make('wiki.index', compact('categories', 'recentArticles', 'wikiTitle', 'welcomeTitle', 'welcomeText'));
    }

    public function category($slug)
    {
        $category = WikiCategory::where('slug', $slug)->firstOrFail();
        $categories = WikiCategory::orderBy('order')->get();
        $articles = $category->publishedArticles;

        return ThemeView::make('wiki.category', compact('category', 'categories', 'articles'));
    }

    public function show($slug)
    {
        $article = WikiArticle::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $categories = WikiCategory::orderBy('order')->get();
        $relatedArticles = $article->relatedArticles;

        return ThemeView::make('wiki.show', compact('article', 'categories', 'relatedArticles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $categories = WikiCategory::orderBy('order')->get();

        $articles = WikiArticle::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->get();

        return ThemeView::make('wiki.search', compact('articles', 'categories', 'query'));
    }
}
