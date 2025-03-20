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
        ]);
        
        $version = new Version();
        $version->version_number = $validated['version_number'];
        $version->release_date = $validated['release_date'];
        // Suppression de la ligne qui utilise description
        $version->content = $validated['content'];
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
        ]);
        
        $version->version_number = $validated['version_number'];
        $version->release_date = $validated['release_date'];
        // Suppression de la ligne qui utilise description
        $version->content = $validated['content'];
        $version->save();
        
        return redirect()->route('admin.changelog')->with('success', 'Version mise à jour avec succès.');
    }

    public function destroy(Version $version)
    {
        $version->delete();
        return redirect()->route('admin.changelog')->with('success', 'Version supprimée avec succès.');
    }
}
