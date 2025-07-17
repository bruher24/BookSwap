<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'name' => 'email_notifications',
                'label' => 'Уведомления по Email',
                'description' => 'Email Notifications',
            ],
            [
                'name' => 'sms_notifications',
                'label' => 'Уведомления по SMS',
                'description' => 'SMS Notifications',
            ]
        ];
        collect($settings)->each(function ($setting) {
            Setting::create($setting);
        });
    }
}