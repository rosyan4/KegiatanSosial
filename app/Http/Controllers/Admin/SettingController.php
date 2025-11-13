<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::ordered()->get()->groupBy('group');
        $groups = Setting::select('group')->distinct()->get()->pluck('group');

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        $validated = [];
        foreach ($settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                // Validate based on setting type
                if ($this->validateSettingValue($setting, $value)) {
                    $validated[$key] = $value;
                }
            }
        }

        Setting::updateMultiple($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function updateGroup(Request $request, $group)
    {
        $validated = $request->validate(
            $this->getValidationRules($group)
        );

        Setting::updateMultiple($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan ' . $this->getGroupLabel($group) . ' berhasil diperbarui.');
    }

    public function initializeDefaults()
    {
        Setting::initializeDefaults();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan default berhasil diinisialisasi.');
    }

    // PERBAIKAN: Method validation yang sesuai dengan model
    private function getValidationRules($group)
    {
        $settings = Setting::group($group)->get();
        $rules = [];
        foreach ($settings as $setting) {
            $rules[$setting->key] = $this->getSettingValidationRules($setting);
        }
        return $rules;
    }

    private function getSettingValidationRules(Setting $setting)
    {
        $rules = [];

        switch($setting->type) {
            case Setting::TYPE_BOOLEAN:
                $rules[] = 'boolean';
                break;
            case Setting::TYPE_INTEGER:
                $rules[] = 'integer';
                break;
            case Setting::TYPE_JSON:
                $rules[] = 'json';
                break;
            case Setting::TYPE_SELECT:
                if ($setting->hasOptions()) {
                    $rules[] = 'in:' . implode(',', array_keys($setting->options));
                }
                break;
        }

        return array_merge($rules, ['nullable']);
    }

    private function validateSettingValue(Setting $setting, $value)
    {
        switch($setting->type) {
            case Setting::TYPE_BOOLEAN:
                return is_bool($value) || in_array($value, [0, 1, '0', '1'], true);
            case Setting::TYPE_INTEGER:
                return is_numeric($value);
            case Setting::TYPE_JSON:
                return is_array($value) || json_validate($value);
            case Setting::TYPE_SELECT:
                return $setting->hasOptions() && array_key_exists($value, $setting->options);
            default:
                return true;
        }
    }

    protected function getGroupLabel($group)
    {
        $labels = [
            'general' => 'Pengaturan Umum',
            'app' => 'Aplikasi Mobile',
            'email' => 'Email & SMTP',
            'notification' => 'Notifikasi',
            'security' => 'Keamanan',
            'payment' => 'Pembayaran',
            'social' => 'Media Sosial',
            'api' => 'API & Integrasi',
            'system' => 'Sistem',
            'appearance' => 'Tampilan & UI',
        ];

        return $labels[$group] ?? ucfirst(str_replace('_', ' ', $group));
    }

    protected function getGroupIcon($group)
    {
        $icons = [
            'general' => 'cog',
            'app' => 'mobile-alt',
            'email' => 'envelope',
            'notification' => 'bell',
            'security' => 'shield-alt',
            'payment' => 'credit-card',
            'social' => 'share-alt',
            'api' => 'code',
            'system' => 'server',
            'appearance' => 'palette',
        ];

        return $icons[$group] ?? 'cog';
    }

    protected function getGroupDescription($group)
    {
        $descriptions = [
            'general' => 'Pengaturan umum aplikasi dan konfigurasi dasar',
            'app' => 'Konfigurasi aplikasi mobile dan pengaturan tampilan',
            'email' => 'Konfigurasi server email dan template',
            'notification' => 'Pengaturan notifikasi dan preferensi',
            'security' => 'Pengaturan keamanan dan autentikasi',
            'payment' => 'Konfigurasi gateway pembayaran',
            'social' => 'Integrasi media sosial dan sharing',
            'api' => 'Pengaturan API dan integrasi eksternal',
            'system' => 'Pengaturan sistem dan performa',
            'appearance' => 'Kustomisasi tampilan dan tema',
        ];

        return $descriptions[$group] ?? 'Pengaturan konfigurasi sistem';
    }
}