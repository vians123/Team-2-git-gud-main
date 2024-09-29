<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BulkDeleteUserTest extends TestCase
{
    /** @var array */
    protected static $IDS;

    /** @var \App\Models\User */
    protected static $USER;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $i = 0;
        $ids = [];
        while ($i < 2) {
            $hash = uniqid();
            $user = User::create(
                [
                    'first_name' => "Johnny{$i}",
                    'last_name' => "Dope{$i}",
                    'email' => "johnny.{$hash}@tcg.sprobe.ph",
                    'password' => 'n3wp@ssw0rd',
                    'user_status_id' => 1,
                ]
            );
            self::$IDS[] = $user->id;
            $i++;
        }

        self::$USER = User::find(1);
    }

    public function __construct($name = 'BulkDeleteUserTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testBulkDeleteNoIds()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('DELETE', '/v1/users/bulk-delete', ['ids' => []]);
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'ids' => [__('validation.required', ['attribute' => 'ids'])]
                ]
            ]);
    }

    public function testBulkDelete()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('DELETE', '/v1/users/bulk-delete', ['ids' => self::$IDS]);
        $response->assertStatus(200)
            ->assertJson([
                'deleted' => true,
            ]);
    }

    public function testBulkDeleteNonExistingIds()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('DELETE', '/v1/users/bulk-delete', [
                            'ids' => [999999999998, 999999999999]
                        ]);
        $response->assertStatus(200)
            ->assertJson([
                'deleted' => false,
            ]);
    }
}
