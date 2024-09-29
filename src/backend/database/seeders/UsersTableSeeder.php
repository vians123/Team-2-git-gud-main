<?php

namespace Database\Seeders;

use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // retrieve user status
        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        // create the system admin
        $this->_createSystemAdmin();

        if (in_array(env('APP_ENV'), ['local', 'development'])) {
            User::factory()
                ->times(50)
                ->create([
                    'user_status_id' => $status->id,
                ])
                ->each(function ($user) {
                    $user->assignRole('User');
                });
        }
    }

    private function _createSystemAdmin(): void
    {
        // retrieve user status
        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        // create the system admin
        $user = User::create([
            'first_name' => 'Sprobe',
            'last_name' => 'Administrator',
            'email' => 'admin@tcg.sprobe.ph',
            'password' => Hash::make('Password2022!'),
            'user_status_id' => $status->id,
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('System Admin');
    }
}
