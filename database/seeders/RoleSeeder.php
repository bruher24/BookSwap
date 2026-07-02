<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
            ],
            [
                'name' => 'user',
            ],
        ];
        collect($roles)->each(function (array $role): void {
            Role::query()->updateOrCreate(['name' => $role['name']], $role);
        });
    }
}
