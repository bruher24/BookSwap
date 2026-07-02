<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

final class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::query()->where('email', 'seller@seller.com')->firstOrFail();
        $buyer = User::query()->where('email', 'buyer@buyer.com')->firstOrFail();
        $admin = User::query()->where('email', 'admin@admin.com')->firstOrFail();

        $ratings = [
            [
                'user_id' => $seller->id,
                'rater_id' => $buyer->id,
                'rate' => 5,
            ],
            [
                'user_id' => $seller->id,
                'rater_id' => $admin->id,
                'rate' => 4,
            ],
            [
                'user_id' => $buyer->id,
                'rater_id' => $seller->id,
                'rate' => 5,
            ],
        ];

        foreach ($ratings as $rating) {
            Rating::query()->updateOrCreate(
                [
                    'user_id' => $rating['user_id'],
                    'rater_id' => $rating['rater_id'],
                ],
                ['rate' => $rating['rate']]
            );
        }

        foreach ([$seller, $buyer] as $user) {
            $user->update([
                'rating' => $user->ratings()->avg('rate') ?? 0,
            ]);
        }
    }
}
