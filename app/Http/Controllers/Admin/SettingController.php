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
            'external_link_url' => 'required|url',
            'external_link_text' => 'required|string|max:255',
        ]);
        
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }
    
    /**
     * Met à jour un paramètre individuel via AJAX
     */
    public function toggle($key)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Paramètre non trouvé'
            ], 404);
        }
        
        $newValue = $setting->value == '1' ? '0' : '1';
        $setting->value = $newValue;
        $setting->save();
        
        return response()->json([
            'success' => true,
            'value' => $newValue,
            'message' => $newValue == '1' ? 'Fonctionnalité activée' : 'Fonctionnalité désactivée'
        ]);
    }
}