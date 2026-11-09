<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** @var list<string> */
    private const TOGGLES = [
        'external_link_enabled',
        'app_store_enabled',
        'play_store_enabled',
    ];

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

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        foreach (self::TOGGLES as $key) {
            Setting::setValue($key, $request->input($key) === '1' ? '1' : '0');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', __('app.flash.settings_saved'));
    }
    
    /**
     * Met à jour un paramètre individuel via AJAX
     */
    public function toggle(Request $request)
    {
        $key = (string) $request->input('key');
        if (! in_array($key, self::TOGGLES, true)) {
            abort(404);
        }

        $current = Setting::getValue($key, '0');
        $newValue = $current == '1' ? '0' : '1';
        $setting = Setting::setValue($key, $newValue);

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