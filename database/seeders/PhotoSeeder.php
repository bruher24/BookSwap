<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;

final class PhotoSeeder extends Seeder
{
    public function run(): void
    {
        $photos = [
            [
                'src' => 'avatars/avatar.png',
                'user_id' => 1,
            ],
        ];

        collect($photos)->each(function ($photo) {
            Photo::create($photo);
        });
    }
}
