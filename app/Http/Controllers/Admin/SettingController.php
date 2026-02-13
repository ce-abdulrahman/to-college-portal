<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $settings = Setting::allAsArray();
        $socialAccounts = Setting::getJson('social_accounts', []);
        $featurePrices = Setting::getJson('feature_prices', [
            '1' => 3000,
            '2' => 5000,
            '3' => 6000,
        ]);

        $fontOptions = [
            'ku' => $this->getFontFiles('ku'),
            'ar' => $this->getFontFiles('ar'),
            'en' => $this->getFontFiles('en'),
        ];

        $systems = System::orderBy('name')->get();

        return view('website.web.admin.settings.index', compact(
            'settings',
            'socialAccounts',
            'featurePrices',
            'fontOptions',
            'systems'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:120',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'remove_logo' => 'nullable|boolean',
            'copyright' => 'nullable|string|max:200',

            'social_name' => 'nullable|array',
            'social_name.*' => 'nullable|string|max:50',
            'social_icon' => 'nullable|array',
            'social_icon.*' => 'nullable|string|max:80',
            'social_url' => 'nullable|array',
            'social_url.*' => 'nullable|string|max:255',

            'font_ku' => 'nullable|string|max:255',
            'font_ar' => 'nullable|string|max:255',
            'font_en' => 'nullable|string|max:255',

            'default_system_id' => 'nullable|integer|exists:systems,id',

            'price_1' => 'nullable|integer|min:0',
            'price_2' => 'nullable|integer|min:0',
            'price_3' => 'nullable|integer|min:0',
        ]);

        // Branding
        Setting::setValue('site_name', $validated['site_name'] ?? null);
        Setting::setValue('copyright', $validated['copyright'] ?? null);

        $currentLogo = Setting::getValue('site_logo');
        if (!empty($validated['remove_logo']) && $currentLogo) {
            $this->deletePublicFile($currentLogo);
            Setting::setValue('site_logo', null);
        }

        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $uploadDir = public_path('uploads/settings');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = 'logo_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $logoPath = 'uploads/settings/' . $fileName;

            if ($currentLogo && $currentLogo !== $logoPath) {
                $this->deletePublicFile($currentLogo);
            }

            Setting::setValue('site_logo', $logoPath);
        }

        // Social accounts
        $socialAccounts = [];
        $names = $request->input('social_name', []);
        $icons = $request->input('social_icon', []);
        $urls = $request->input('social_url', []);
        $max = max(count($names), count($icons), count($urls));

        for ($i = 0; $i < $max; $i++) {
            $name = trim($names[$i] ?? '');
            $icon = trim($icons[$i] ?? '');
            $url = trim($urls[$i] ?? '');
            if ($name === '' && $icon === '' && $url === '') {
                continue;
            }
            $socialAccounts[] = [
                'name' => $name,
                'icon' => $icon,
                'url' => $url,
            ];
        }
        Setting::setJson('social_accounts', $socialAccounts);

        // Fonts
        Setting::setValue('font_ku', $validated['font_ku'] ?? null);
        Setting::setValue('font_ar', $validated['font_ar'] ?? null);
        Setting::setValue('font_en', $validated['font_en'] ?? null);

        // Default system
        if (!empty($validated['default_system_id'])) {
            Setting::setValue('default_system_id', (string) $validated['default_system_id']);
        }

        // Feature prices
        $featurePrices = [
            '1' => (int) ($validated['price_1'] ?? 3000),
            '2' => (int) ($validated['price_2'] ?? 5000),
            '3' => (int) ($validated['price_3'] ?? 6000),
        ];
        Setting::setJson('feature_prices', $featurePrices);

        return redirect()->back()->with('success', 'ڕێکخستنەکان بە سەرکەوتوویی پاشەکەوت کران.');
    }

    private function getFontFiles(string $lang): array
    {
        $path = public_path('fonts/' . $lang);
        if (!is_dir($path)) {
            return [];
        }

        return collect(File::files($path))
            ->map(function ($file) use ($lang) {
                return 'fonts/' . $lang . '/' . $file->getFilename();
            })
            ->values()
            ->all();
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }
        $fullPath = public_path($relativePath);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
