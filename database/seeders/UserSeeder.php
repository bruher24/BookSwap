<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('1234'),
            ],
            [
                'name' => 'Super',
                'email' => 'admin@super.com',
                'password' => Hash::make('1234'),
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@seller.com',
                'password' => Hash::make('1234'),
            ],
            [
                'name' => 'Buyer',
                'email' => 'buyer@buyer.com',
                'password' => Hash::make('1234'),
            ],
        ];
        collect($users)->each(function ($userData) {
            $user = new User($userData);
            $user->save();
            $user->refresh();
            $user->roles()->attach($user->id == 1 ? 1 : 2);
            $user->settings()->attach(Setting::all()->first(), ['value' => 'on']);
            $user->save();
        });
    }
}
