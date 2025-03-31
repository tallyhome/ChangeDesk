<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog.index', compact('versions'));
    }

    public function create()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog.create', compact('versions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'version_number' => 'required|string|max:20',
            'release_date' => 'required|date',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $version = new Version();
        $version->version_number = $validated['version_number'];
        $version->release_date = $validated['release_date'];
        $version->description = ''; // Ajout d'une valeur par défaut pour description
        $version->content = $validated['content'];
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
            $version->image_path = 'uploads/' . $imageName;
        }
        
        $version->save();
        
        return redirect()->route('admin.changelog')->with('success', 'Version ajoutée avec succès.');
    }

    public function edit(Version $version)
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog.edit', compact('version', 'versions'));
    }

    // J'ai gardé cette version de la méthode update et supprimé la seconde
    public function update(Request $request, Version $version)
    {
        $validated = $request->validate([
            'version_number' => 'required|string|max:20',
            'release_date' => 'required|date',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $version->version_number = $validated['version_number'];
        $version->release_date = $validated['release_date'];
        // Nous conservons la valeur existante de description
        $version->content = $validated['content'];
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($version->image_path && file_exists(public_path($version->image_path))) {
                unlink(public_path($version->image_path));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $imageName);
            $version->image_path = 'uploads/' . $imageName;
        }
        
        $version->save();
        
        return redirect()->route('admin.changelog')->with('success', 'Version mise à jour avec succès.');
    }

    public function destroy(Version $version)
    {
        $version->delete();
        return redirect()->route('admin.changelog')->with('success', 'Version supprimée avec succès.');
    }

    public function toggleChangelogStatus()
    {
        $setting = \App\Models\Setting::where('key', 'changelog_enabled')->first();
        
        if (!$setting) {
            $setting = new \App\Models\Setting();
            $setting->key = 'changelog_enabled';
            $setting->value = '1';
        }
        
        $setting->value = $setting->value === '1' ? '0' : '1';
        $setting->save();
        
        return response()->json([
            'success' => true,
            'value' => $setting->value,
            'message' => $setting->value === '1' ? 'Changelog activé' : 'Changelog désactivé'
        ]);
    }
}
