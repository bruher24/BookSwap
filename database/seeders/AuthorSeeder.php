<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

final class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            [
                'lastname' => 'Тестов',
                'firstname' => 'Автор',
                'patronymic' => 'Книгович',
                'birthdate' => '1990-01-01',
            ],
            [
                'lastname' => 'Пушкин',
                'firstname' => 'Александр',
                'patronymic' => 'Сергеевич',
                'birthdate' => '1990-01-01',
            ],
            [
                'lastname' => 'Лавкрафт',
                'firstname' => 'Говард',
                'birthdate' => '1990-01-01',
            ],
            [
                'lastname' => 'Министерство',
                'firstname' => 'Образования',
                'birthdate' => '1991-01-01',
            ],
        ];
        collect($authors)->each(function ($author) {
            Author::create($author);
        });
    }
}
