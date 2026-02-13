<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\System;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::setValue('site_name', 'بۆ کۆلێژ');
        Setting::setValue('site_logo', 'images/logo.png');
        Setting::setValue('copyright', 'مافی ئەم سیستەمە پارێزاوە بۆ، ئەندازیار عبدالرحمن');

        Setting::setJson('social_accounts', [
            ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => 'https://www.facebook.com/AghaAS7421'],
            ['name' => 'Telegram', 'icon' => 'fab fa-telegram-plane', 'url' => 'https://t.me/AGHA_ACE'],
            ['name' => 'Whatsapp', 'icon' => 'fab fa-whatsapp', 'url' => 'https://wa.me/9647504342452'],
            ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://www.instagram.com/agha_ace'],
            ['name' => 'Viber', 'icon' => 'fab fa-viber', 'url' => 'viber://chat?number=9647504342452'],
        ]);

        Setting::setJson('feature_prices', [
            '1' => 3000,
            '2' => 5000,
            '3' => 6000,
        ]);

        $defaultSystem = System::where('status', 1)->first();
        if ($defaultSystem) {
            Setting::setValue('default_system_id', (string) $defaultSystem->id);
        }
    }
}

