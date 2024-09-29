<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionSearchTest extends TestCase
{
    /** @var App\Models\User */
    private static $ADMIN;

    /** @var App\Models\User */
    private static $USER;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // set admin details
        self::$ADMIN = User::find(1);
        // normal user
        self::$USER = User::find(2);
    }

    /**
     * PermissionSearchTest constructor.
     */
    public function __construct($name = 'PermissionSearchTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testSearchNotAdmin()
    {
        $response = $this->actingAs(self::$USER, 'api')
                ->json('GET', '/' . config('app.api_version') . '/permissions');
        $response->assertStatus(403)
            ->assertJsonFragment([
                'error' => __('exception.unauthorized'),
            ]);
    }

    public function testSearch()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('GET', '/' . config('app.api_version') . '/permissions');
        $response->assertStatus(200);
        $expected = array_merge(config('acl.roles')['System Admin'], config('acl.roles')['User']);
        $permissions = $response->getData()->data;

        foreach ($permissions as $permission) {
            $this->assertTrue(in_array($permission->name, $expected));
        }
    }
}
