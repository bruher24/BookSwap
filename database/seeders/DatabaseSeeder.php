<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @psalm-suppress UnusedClass
 */
final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            PhotoSeeder::class,
            ChatSeeder::class,
            MessageSeeder::class,
            GenreSeeder::class,
            AuthorSeeder::class,
            CoverSeeder::class,
            BookTypeSeeder::class,
            BookSeeder::class,
            TradeOfferSeeder::class,
            FilterSeeder::class,
            RatingSeeder::class,
        ]);
    }
}
