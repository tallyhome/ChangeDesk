<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'external_link_url' => 'nullable|url',
            'external_link_text' => 'nullable|string|max:255',
            'app_store_url' => 'nullable|url',
            'play_store_url' => 'nullable|url',
        ]);
        
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour avec succès');
    }
}