<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        $authors = [
            [
                'lastname' => 'test 1',
                'firstname' => 'test 1',
                'patronymic' => 'test 1',
                'birthdate' => '1990-01-01',
            ],
            [
                'lastname' => 'test 2',
                'firstname' => 'test 2',
                'patronymic' => 'test 2',
                'birthdate' => '1990-01-01',
            ],
            [
                'lastname' => 'test 3',
                'firstname' => 'test 3',
                'patronymic' => 'test 3',
                'birthdate' => '1990-01-01',
            ],
        ];
        collect($authors)->each(function ($author) {
           Author::create($author);
        });
    }
}