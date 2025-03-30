<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WikiController extends Controller
{
    /**
     * Affiche la liste des articles wiki
     */
    public function index()
    {
        $articles = WikiArticle::with('category')->orderBy('order')->paginate(15);
        return view('admin.wiki.index', compact('articles'));
    }

    /**
     * Affiche le formulaire de création d'un article
     */
    public function create()
    {
        $categories = WikiCategory::orderBy('name')->get();
        return view('admin.wiki.create', compact('categories'));
    }

    /**
     * Enregistre un nouvel article
     */
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
        $article->content = $validated['content'];
        $article->slug = Str::slug($validated['title']);
        $article->wiki_category_id = $validated['wiki_category_id'] ?? null;
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $request->has('is_published');
        $article->save();

        return redirect()->route('admin.wiki.index')
            ->with('success', 'Article créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un article
     */
    public function edit(WikiArticle $article)
    {
        $categories = WikiCategory::orderBy('name')->get();
        return view('admin.wiki.edit', compact('article', 'categories'));
    }

    /**
     * Met à jour un article
     */
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
        $article->content = $validated['content'];
        
        // Mettre à jour le slug uniquement si le titre a changé
        if ($article->title !== $validated['title']) {
            $article->slug = Str::slug($validated['title']);
        }
        
        $article->wiki_category_id = $validated['wiki_category_id'] ?? null;
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $request->has('is_published');
        $article->save();

        return redirect()->route('admin.wiki.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Supprime un article
     */
    public function destroy(WikiArticle $article)
    {
        $article->delete();
        return redirect()->route('admin.wiki.index')
            ->with('success', 'Article supprimé avec succès.');
    }

    /**
     * Affiche un article
     */
    public function show(WikiArticle $article)
    {
        return view('admin.wiki.show', compact('article'));
    }

    /**
     * Affiche la liste des catégories
     */
    public function categories()
    {
        $categories = WikiCategory::withCount('articles')->orderBy('order')->get();
        return view('admin.wiki.categories.index', compact('categories'));
    }

    /**
     * Affiche le formulaire de création d'une catégorie
     */
    public function createCategory()
    {
        return view('admin.wiki.categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     */
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
        $category->description = $validated['description'] ?? null;
        $category->order = $validated['order'] ?? 0;
        $category->save();

        return redirect()->route('admin.wiki.categories')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie
     */
    public function editCategory(WikiCategory $category)
    {
        return view('admin.wiki.categories.edit', compact('category'));
    }

    /**
     * Met à jour une catégorie
     */
    public function updateCategory(Request $request, WikiCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer'
        ]);

        $category->name = $validated['name'];
        
        // Mettre à jour le slug uniquement si le nom a changé
        if ($category->name !== $validated['name']) {
            $category->slug = Str::slug($validated['name']);
        }
        
        $category->description = $validated['description'] ?? null;
        $category->order = $validated['order'] ?? 0;
        $category->save();

        return redirect()->route('admin.wiki.categories')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprime une catégorie
     */
    public function destroyCategory(WikiCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.wiki.categories')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}