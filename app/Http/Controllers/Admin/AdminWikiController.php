<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Setting;

class AdminWikiController extends Controller
{
    public function index(Request $request)
    {
        $query = WikiArticle::with('category')
            ->orderBy('order');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(10);
        $wikiEnabled = \App\Models\Setting::where('key', 'wiki_enabled')->value('value') ?? true;

        return view('admin.wiki.index', compact('articles', 'wikiEnabled'));
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
            'is_published' => 'required|boolean'
        ]);

        $article = new WikiArticle();
        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']);
        $article->content = $validated['content'];
        $article->wiki_category_id = $validated['wiki_category_id'];
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $validated['is_published'];
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
            'is_published' => 'required|boolean'
        ]);

        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']);
        $article->content = $validated['content'];
        $article->wiki_category_id = $validated['wiki_category_id'];
        $article->order = $validated['order'] ?? 0;
        $article->is_published = $validated['is_published'];
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

    public function togglePublication(WikiArticle $article)
    {
        try {
            \Log::info('Tentative de basculement de publication pour l\'article: ' . $article->id);
            \Log::info('État actuel: ' . ($article->is_published ? 'publié' : 'non publié'));

            $article->is_published = !$article->is_published;
            $article->save();

            \Log::info('Nouvel état: ' . ($article->is_published ? 'publié' : 'non publié'));

            return response()->json([
                'success' => true,
                'is_published' => $article->is_published,
                'message' => $article->is_published ? 'Article publié' : 'Article dépublié'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du basculement de publication: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la modification du statut'
            ], 500);
        }
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
            ->route('admin.wiki.categories.index')
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
            ->route('admin.wiki.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function settings()
    {
        $settings = Setting::whereIn('key', ['wiki_title', 'wiki_welcome_title', 'wiki_welcome_text', 'wiki_enabled'])
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.wiki.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validatedData = $request->validate([
            'wiki_title' => 'required|string|max:255',
            'wiki_welcome_title' => 'required|string|max:255',
            'wiki_welcome_text' => 'nullable|string'
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.wiki.settings')
            ->with('success', 'Paramètres du wiki mis à jour avec succès.');
    }

    public function destroyCategory(WikiCategory $category)
    {
        if ($category->articles()->count() > 0) {
            return redirect()
                ->route('admin.wiki.categories.index')
                ->with('error', 'Impossible de supprimer une catégorie contenant des articles.');
        }

        $category->delete();

        return redirect()
            ->route('admin.wiki.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    public function toggleWikiStatus()
    {
        try {
            $currentValue = Setting::getValue('wiki_enabled', true);
            $newValue = !$currentValue;
            
            $setting = Setting::firstOrCreate(['key' => 'wiki_enabled']);
            $setting->value = $newValue;
            $setting->save();

            \Log::info('Statut du wiki modifié: ' . ($newValue ? 'activé' : 'désactivé'));

            return response()->json([
                'success' => true,
                'is_enabled' => $newValue,
                'message' => $newValue ? 'Wiki activé' : 'Wiki désactivé'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la modification du statut du wiki: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la modification du statut'
            ], 500);
        }
    }
}