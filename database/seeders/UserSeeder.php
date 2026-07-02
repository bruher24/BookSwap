<?php

namespace Database\Seeders;

use App\Models\Role;
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
                'city' => 'Самара',
            ],
            [
                'name' => 'Super',
                'email' => 'admin@super.com',
                'password' => Hash::make('1234'),
                'city' => 'Самара',
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@seller.com',
                'password' => Hash::make('1234'),
                'city' => 'Самара',
            ],
            [
                'name' => 'Buyer',
                'email' => 'buyer@buyer.com',
                'password' => Hash::make('1234'),
                'city' => 'Самара',
            ],
        ];

        $adminRole = Role::query()->where('name', 'admin')->firstOrFail();
        $userRole = Role::query()->where('name', 'user')->firstOrFail();
        $settings = Setting::query()->get();

        collect($users)->each(function (array $userData) use ($adminRole, $userRole, $settings): void {
            $user = User::query()->updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->roles()->syncWithoutDetaching([
                $user->email === 'admin@admin.com' ? $adminRole->id : $userRole->id,
            ]);

            foreach ($settings as $setting) {
                $user->settings()->syncWithoutDetaching([
                    $setting->id => ['value' => $setting->available_values[0] ?? 'on'],
                ]);
            }
        });
    }
}
