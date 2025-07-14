<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    public function run()
    {
        $photos = [
            [
                'src' => 'storage/app/public/avatar.png',
            ],
        ];

        collect($photos)->each(function ($photo) {
            Photo::create($photo);
        });
    }
}