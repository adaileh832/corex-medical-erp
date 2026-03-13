<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'hospital_name' => 'مستشفى الأقصى',
            'hospital_name_en' => 'Aqsa Hospital',
            'phone' => '',
            'address' => '',
            'logo' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}