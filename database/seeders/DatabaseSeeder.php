<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            PhotoSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            PhoneSeeder::class,
            GenreSeeder::class,
            AuthorSeeder::class,
            CoverSeeder::class,
            BookSeeder::class,
        ]);
    }
}
