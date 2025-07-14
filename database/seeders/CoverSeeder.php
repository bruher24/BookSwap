<?php

namespace Database\Seeders;

use App\Models\Cover;
use Illuminate\Database\Seeder;

class CoverSeeder extends Seeder
{
    public function run(){
        $covers = [
            'src' => 'storage/app/public/',
        ];

        collect($covers)->each(function($cover){
            Cover::create($cover);
        });
    }
}