<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\UserStatus;

class UserStatusTest extends TestCase
{
    public function testGetAllUsersByStatus()
    {
        $status = UserStatus::with('users')->find(1);
        // call to Array since default is Collection Class by Laravel
        $this->assertTrue(is_array($status->users->toArray()));

        foreach ($status->users as $user) {
            $this->assertEquals($user->status->name, $status->name);
        }
    }
}
