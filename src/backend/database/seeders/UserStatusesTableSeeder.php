<?php

namespace Database\Seeders;

use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UserStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [];

        foreach (config('user.statuses') as $key => $value) {
            $statuses[] = ['name' => $value];
        }

        UserStatus::insert($statuses);
    }
}
