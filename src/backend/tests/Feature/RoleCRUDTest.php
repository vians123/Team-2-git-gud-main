<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleCRUDTest extends TestCase
{
    /** @var array */
    private $data;

    /** @var App\Models\User */
    private static $ADMIN;

    /** @var App\Models\User */
    private static $USER;

    /** @var array */
    private static $ROLES;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // set admin details
        self::$ADMIN = User::find(1);
        self::$USER = User::find(2);
    }

    /**
     * RoleCRUDTest constructor.
     */
    public function __construct($name = 'RoleCRUDTest')
    {
        parent::__construct($name);
        $this->createApplication();

        // test variables
        $this->data = [
            'name' => 'Office Admin',
            'permissions' => ['Manage Users', 'View Users', 'View Roles'],
        ];
    }

    public function testCreateWithMissingParams()
    {
        $params = $this->data;
        unset($params['name']);
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('POST', '/' . config('app.api_version') . '/roles', $params);
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'name' => [__('validation.required', ['attribute' => 'name'])]
                ]
            ]);
    }

    public function testCreateUserNoPermission()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('POST', '/v1/roles', $this->data);
        $response->assertStatus(403)
            ->assertJsonFragment([
                'error' => __('exception.unauthorized'),
            ]);
    }

    public function testCreate()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('POST', '/v1/roles', $this->data);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->data['name'],
            ]);
        $role = $response->getData()->data;
        self::$ROLES[] = $role->id;

        // verify if permissions are attached properly
        $this->assertEquals(3, count($role->permissions));
        foreach ($role->permissions as $permission) {
            $this->assertTrue(in_array($permission->name, $this->data['permissions']));
        }
    }

    public function testCreateWithNoAttachedPermissions()
    {
        $params = [
            'name' => 'Secretary',
            'permissions' => [],
        ];

        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('POST', '/v1/roles', $params);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $params['name'],
            ]);
        $role = $response->getData()->data;
        self::$ROLES[] = $role->id;
        $this->assertEquals(0, count($role->permissions));
    }

    public function testViewUserNoPermission()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('GET', '/v1/roles/' . self::$ROLES[0]);
        $response->assertStatus(403)
            ->assertJsonFragment([
                'error' => __('exception.unauthorized'),
            ]);
    }

    public function testViewNonExistingRole()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('GET', '/v1/roles/999999999');
        $response->assertStatus(500)
            ->assertJsonFragment([
                'error' => __('exception.not_found', ['model' => 'Role']),
            ]);
    }

    public function testView()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('GET', '/v1/roles/' . self::$ROLES[0]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->data['name'],
            ]);
    }

    public function testUpdateWithMissingParams()
    {
        $params = $this->data;
        unset($params['name']);
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('PUT', '/v1/roles/' . self::$ROLES[0]);
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'name' => [__('validation.required', ['attribute' => 'name'])]
                ]
            ]);
    }

    public function testUpdateUserNoPermission()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('PUT', '/v1/roles/' . self::$ROLES[0], $this->data);
        $response->assertStatus(403)
                ->assertJsonFragment([
                    'error' => __('exception.unauthorized'),
                ]);
    }

    public function testUpdate()
    {
        $params = [
            'name' => 'Assistant',
            'permissions' => ['View Users', 'View Roles'],
        ];

        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('PUT', '/v1/roles/' . self::$ROLES[0], $params);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $params['name'],
            ]);
        $role = $response->getData()->data;

        // verify if permissions are attached properly
        $this->assertEquals(2, count($role->permissions));
        foreach ($role->permissions as $permission) {
            $this->assertTrue(in_array($permission->name, $params['permissions']));
        }
    }

    public function testUpdateNoAttachedPermissions()
    {
        $params = [
            'name' => 'Assistant V2',
            'permissions' => [],
        ];

        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('PUT', '/v1/roles/' . self::$ROLES[0], $params);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $params['name'],
            ]);
        $role = $response->getData()->data;

        // verify if permissions are empty
        $this->assertEquals(0, count($role->permissions));
    }

    public function testDeleteUserNoPermission()
    {
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('DELETE', '/v1/roles/' . self::$ROLES[0]);
        $response->assertStatus(403)
            ->assertJsonFragment([
                'error' => __('exception.unauthorized'),
            ]);
    }

    public function testDeleteNonExistingRole()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('DELETE', '/v1/roles/999999999');
        $response->assertStatus(500)
            ->assertJsonFragment([
                'error' => __('exception.not_found', ['model' => 'Role']),
            ]);
    }

    public function testDelete()
    {
        foreach (self::$ROLES as $id) {
            $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('DELETE', '/v1/roles/' . $id);
            $response->assertStatus(200)
                ->assertJsonFragment([
                    'deleted' => true,
                ]);
        }
    }
}
