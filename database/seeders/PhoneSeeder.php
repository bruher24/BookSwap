<?php

namespace Database\Seeders;

use App\Models\Phone;
use Illuminate\Database\Seeder;

final class PhoneSeeder extends Seeder
{
    public function run(): void
    {
        $phones = [
            [
                'number' => '79998887766',
                'user_id' => 1,
            ],
        ];

        collect($phones)->each(function ($phone) {
            Phone::create($phone);
        });
    }
}
