<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $defaultSettings = [
            'external_link_enabled' => '0',
            'external_link_text' => '',
            'external_link_url' => '',
            'app_store_enabled' => '0',
            'app_store_url' => '',
            'play_store_enabled' => '0',
            'play_store_url' => ''
        ];

        $settings = Setting::pluck('value', 'key')->all();
        $settings = array_merge($defaultSettings, $settings);

        return view('admin.settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'external_link_text' => 'nullable|string|max:255',
            'external_link_url' => 'nullable|url|max:255',
            'app_store_url' => 'nullable|url|max:255',
            'play_store_url' => 'nullable|url|max:255'
        ]);

        // Mettre à jour les paramètres textuels
        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Mettre à jour les états des toggles
        $toggles = [
            'external_link_enabled',
            'app_store_enabled',
            'play_store_enabled'
        ];

        foreach ($toggles as $key) {
            // Utiliser input() au lieu de has() pour récupérer la valeur réelle du champ
            // Cela prendra en compte les champs cachés avec value="0"
            $value = $request->input($key, '0');
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Les paramètres ont été mis à jour avec succès.');
    }
    
    /**
     * Met à jour un paramètre individuel via AJAX
     */
    public function toggle(Request $request)
    {
        $key = $request->input('key');
        $setting = Setting::firstOrCreate(
            ['key' => $key],
            ['value' => '0']
        );

        $newValue = $setting->value == '1' ? '0' : '1';
        $setting->value = $newValue;
        $setting->save();

        $messages = [
            'external_link_enabled' => $newValue == '1' ? 'Lien externe activé' : 'Lien externe désactivé',
            'app_store_enabled' => $newValue == '1' ? 'Icône App Store activée' : 'Icône App Store désactivée',
            'play_store_enabled' => $newValue == '1' ? 'Icône Play Store activée' : 'Icône Play Store désactivée'
        ];

        return response()->json([
            'success' => true,
            'value' => $newValue,
            'message' => $messages[$key] ?? 'Paramètre mis à jour avec succès'
        ]);
    }
}