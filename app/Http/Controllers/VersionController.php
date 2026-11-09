<?php

namespace App\Http\Controllers;

use App\Models\Version;
use App\Services\ReleaseNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $version->image_path = $this->storeVersionImage($request->file('image'));
        }
        
        $version->save();

        app(ReleaseNotifier::class)->created($version);
        
        return redirect()->route('admin.changelog')->with('success', __('app.flash.version_added'));
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
            $this->deleteVersionImage($version->image_path);
            $version->image_path = $this->storeVersionImage($request->file('image'));
        }
        
        $version->save();
        
        return redirect()->route('admin.changelog')->with('success', __('app.flash.version_updated'));
    }

    public function destroy(Version $version)
    {
        $version->delete();
        return redirect()->route('admin.changelog')->with('success', __('app.flash.version_deleted'));
    }

    private function storeVersionImage($image): string
    {
        $ext = strtolower((string) $image->guessExtension());
        $allowed = ['jpg' => 'jpg', 'jpeg' => 'jpg', 'png' => 'png', 'gif' => 'gif'];
        abort_unless(isset($allowed[$ext]), 422);

        $dir = public_path('uploads');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = time().'_'.Str::random(10).'.'.$allowed[$ext];
        $image->move($dir, $name);

        return 'uploads/'.$name;
    }

    private function deleteVersionImage(?string $path): void
    {
        if (! is_string($path) || ! str_starts_with($path, 'uploads/') || str_contains($path, '..')) {
            return;
        }

        $full = public_path($path);
        if (is_file($full)) {
            unlink($full);
        }
    }

    public function toggleChangelogStatus()
    {
        $current = \App\Models\Setting::getValue('changelog_enabled', '1');
        $value = $current === '1' ? '0' : '1';
        \App\Models\Setting::setValue('changelog_enabled', $value);

        return response()->json([
            'success' => true,
            'value' => $value,
            'message' => $value === '1' ? 'Changelog activé' : 'Changelog désactivé',
        ]);
    }
}
