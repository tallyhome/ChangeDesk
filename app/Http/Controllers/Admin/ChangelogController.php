<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog.index', compact('versions'));
    }
    
    public function destroy($id)
    {
        $version = Version::findOrFail($id);
        $version->delete();
        
        return redirect()->route('admin.changelog')->with('success', 'Version supprimée avec succès');
    }
    
    public function update(Request $request, $id)
    {
        $version = Version::findOrFail($id);
        
        $version->update([
            'version_number' => $request->version_number,
            'release_date' => $request->release_date,
            'content' => $request->content
        ]);
        
        return redirect()->route('admin.changelog')->with('success', 'Version mise à jour avec succès');
    }

    public function create()
    {
        return view('admin.changelog.create');
    }
    
    // Ajout de la méthode store manquante
    public function store(Request $request)
    {
        Version::create([
            'version_number' => $request->version_number,
            'release_date' => $request->release_date,
            'content' => $request->content
        ]);
        
        return redirect()->route('admin.changelog')->with('success', 'Version créée avec succès');
    }
    
    // Ajoutez cette méthode à votre contrôleur
    public function edit($id)
    {
        $version = Version::findOrFail($id);
        return view('admin.changelog.edit', compact('version'));
    }
}