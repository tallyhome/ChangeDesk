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
            'external_link_active' => 'nullable|boolean',
            'app_store_url' => 'nullable|url',
            'app_store_active' => 'nullable|boolean',
            'play_store_url' => 'nullable|url',
            'play_store_active' => 'nullable|boolean',
        ]);
        
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour avec succès');
    }
    
    /**
     * Met à jour un paramètre individuel via AJAX
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'required',
        ]);
        
        Setting::updateOrCreate(
            ['key' => $validated['key']],
            ['value' => $validated['value']]
        );
        
        return response()->json(['success' => true]);
    }
}