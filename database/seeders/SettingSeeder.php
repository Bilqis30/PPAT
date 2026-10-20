<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'wa_gateway_url' => 'https://api.fonnte.com/send',
            'wa_gateway_token' => '',
            'wa_recipient_number' => '',
            'wa_is_active' => '1',
            'wa_reminder_days' => '2',
            'wa_reminder_time' => '07:00',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
