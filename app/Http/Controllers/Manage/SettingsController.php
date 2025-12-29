<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\Settings\Setting;

class SettingsController extends Controller
{
    // only these settings will display in index view
    private array $visibleSettings = ['PRIVACY_POLICY', 'ABOUT_US', 'TERMS_AND_CONDITIONS', 'FAQ', 'STRIPE_KEY', 'STRIPE_SECRET', 'FCM_SERVER_KEY', 'AGORA_APP_ID', 'GOOGLE_MAPS_KEY'];

    public function index()
    {
        $settings = Setting::whereIn('setting_key', $this->visibleSettings)->pluck('setting_value', 'setting_key');

        return view('manage.settings.index', [
            'pageTitle' => 'Settings',
            'settings' => $settings,
            'visibleSettings' => $this->visibleSettings
        ]);
    }

    public function update(Request $request, $id = null)
    {
        $data = $request->only($this->visibleSettings);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => $value]
            );
        }

        return back()->with('success', 'Settings Updated');
    }
}