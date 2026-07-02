<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();

        $authors = [
            [
                'lastname' => 'Тестов',
                'firstname' => 'Автор',
                'patronymic' => 'Книгович',
                'birthdate' => '1990-01-01',
                'user_id' => $admin->id,
            ],
            [
                'lastname' => 'Пушкин',
                'firstname' => 'Александр',
                'patronymic' => 'Сергеевич',
                'birthdate' => '1990-01-01',
                'user_id' => $admin->id,
            ],
            [
                'lastname' => 'Лавкрафт',
                'firstname' => 'Говард',
                'birthdate' => '1990-01-01',
                'user_id' => $admin->id,
            ],
            [
                'lastname' => 'Министерство',
                'firstname' => 'Образования',
                'birthdate' => '1991-01-01',
                'user_id' => $admin->id,
            ],
        ];
        collect($authors)->each(function (array $author): void {
            Author::query()->updateOrCreate(
                [
                    'lastname' => $author['lastname'],
                    'firstname' => $author['firstname'],
                    'patronymic' => $author['patronymic'] ?? null,
                ],
                $author
            );
        });
    }
}
