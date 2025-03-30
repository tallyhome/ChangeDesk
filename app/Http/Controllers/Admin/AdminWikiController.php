<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminWikiController extends Controller
{
    public function index()
    {
        $articles = WikiArticle::with('category')
            ->orderBy('order')
            ->paginate(15);

        return view('admin.wiki.index', compact('articles'));
    }

    public function create()
    {
        $categories = WikiCategory::orderBy('order')->get();
        return view('admin.wiki.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'wiki_category_id' => 'nullable|exists:wiki_categories,id',
            'order' => 'nullable|integer',
            'is_published' => 'boolean'
        ]);

        $article = new WikiArticle();
        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']);
        $article->content = $validated['content'];
        $article->wiki_category_id = $validated['wiki_category_id'];
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $request->has('is_published');
        $article->save();

        return redirect()
            ->route('admin.wiki.index')
            ->with('success', 'Article créé avec succès.');
    }

    public function show(WikiArticle $article)
    {
        return view('admin.wiki.show', compact('article'));
    }

    public function edit(WikiArticle $article)
    {
        $categories = WikiCategory::orderBy('order')->get();
        return view('admin.wiki.edit', compact('article', 'categories'));
    }

    public function update(Request $request, WikiArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'wiki_category_id' => 'nullable|exists:wiki_categories,id',
            'order' => 'nullable|integer',
            'is_published' => 'boolean'
        ]);

        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']);
        $article->content = $validated['content'];
        $article->wiki_category_id = $validated['wiki_category_id'];
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $request->has('is_published');
        $article->save();

        return redirect()
            ->route('admin.wiki.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(WikiArticle $article)
    {
        $article->delete();

        return redirect()
            ->route('admin.wiki.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    public function preview(Request $request)
    {
        $content = $request->input('content');
        return response()->json(['preview' => $content]);
    }

    // Gestion des catégories
    public function categories()
    {
        $categories = WikiCategory::withCount('articles')
            ->orderBy('order')
            ->get();

        return view('admin.wiki.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.wiki.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $category = new WikiCategory();
        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->description = $validated['description'];
        $category->order = $validated['order'] ?? 0;
        $category->save();

        return redirect()
            ->route('admin.wiki.categories')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function editCategory(WikiCategory $category)
    {
        return view('admin.wiki.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, WikiCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->description = $validated['description'];
        $category->order = $validated['order'] ?? 0;
        $category->save();

        return redirect()
            ->route('admin.wiki.categories')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroyCategory(WikiCategory $category)
    {
        if ($category->articles()->count() > 0) {
            return redirect()
                ->route('admin.wiki.categories')
                ->with('error', 'Impossible de supprimer une catégorie contenant des articles.');
        }

        $category->delete();

        return redirect()
            ->route('admin.wiki.categories')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}