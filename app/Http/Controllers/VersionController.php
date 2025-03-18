<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function index()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog', compact('versions'));
    }

    public function create()
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog', compact('versions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'version_number' => 'required|string|max:255',
            'release_date' => 'required|date',
            'content' => 'required|string'
        ]);

        Version::create($validated);

        return redirect()->route('admin.changelog')->with('success', 'Version créée avec succès');
    }

    public function edit(Version $version)
    {
        $versions = Version::orderBy('release_date', 'desc')->get();
        return view('admin.changelog', compact('version', 'versions'));
    }

    public function update(Request $request, Version $version)
    {
        $validated = $request->validate([
            'version_number' => 'required|string|max:255',
            'release_date' => 'required|date',
            'content' => 'required|string'
        ]);

        $version->update($validated);

        return redirect()->route('admin.changelog')->with('success', 'Version mise à jour avec succès');
    }
}
