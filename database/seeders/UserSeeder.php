<?php

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
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
            $user->photo()->associate(Photo::all()->first());
            $user->save();
        });
    }
}