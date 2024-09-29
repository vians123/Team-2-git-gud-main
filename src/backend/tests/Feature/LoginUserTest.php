<?php

namespace Tests\Feature;

use Hash;
use Config;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserStatus;

class LoginUserTest extends TestCase
{
    /** @var array */
    private $data;

    /** @var array */
    public static $user = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@test.com',
        'password' => '',
        'user_status_id' => 1,
        'login_attempts' => 0,
    ];

    /** @var \App\Models\User */
    private static $MODEL;

    /** @var string */
    private static $password = 'password';

    /**
     * Execute before each test
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::$user['password'] = Hash::make(self::$password);
        self::$MODEL = User::updateOrCreate(
            ['email' => self::$user['email']],
            self::$user
        );
    }

    /**
     * LoginUserTest constructor.
     */
    public function __construct($name = 'LoginUserTest')
    {
        parent::__construct($name);
        $this->createApplication();

        // test variables
        $this->data = [
            'client_id' => (int) config('app.client_id'),
            'client_secret' => config('app.client_secret'),
            'grant_type' => 'password',
            'username' => self::$user['email'],
            'password' => self::$password,
        ];
    }

    /**
     * Run after every test function.
     * @return void
     */
    public function tearDown(): void
    {
        // delete user
        self::$MODEL->delete();

        parent::tearDown();
    }

    /**
     * Invalid Client Id
     * @return void
     */
    public function testInvalidClientId()
    {
        $data = $this->data;
        $data['client_id'] = 1;
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'invalid_client',
            ]);
    }

    /**
     * Invalid Client Secret
     * @return void
     */
    public function testInvalidClientSecret()
    {
        $data = $this->data;
        $data['client_secret'] = uniqid();
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'invalid_client',
            ]);
    }

    /**
     * Invalid Password
     * @return void
     */
    public function testInvalidPassword()
    {
        $data = $this->data;
        $data['password'] = uniqid();
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'invalid_password',
            ]);
    }

    /**
     * Invalid Email / User does not exist.
     * @return void
     */
    public function testInvalidEmail()
    {
        $data = $this->data;
        $data['username'] = uniqid() . '@mail.com';
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'invalid_user_credentials',
            ]);
    }

    /**
     * Missing Parameter(s).
     * @return void
     */
    public function testMissingParams()
    {
        $data = $this->data;
        unset($data['password']);
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(400)
            ->assertJson([
                'error' => 'invalid_request',
            ]);
    }

    public function testUserRepositoryWrongAuthModel()
    {
        $provider = config('auth.guards.api.provider');
        $model = config("auth.providers.{$provider}.model");

        // change auth model
        Config::set("auth.providers.{$provider}.model", null);
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'missing_auth_model',
            ]);

        // restore original auth model
        Config::set("auth.providers.{$provider}.model", $model);
    }

    /**
     * Successful Login
     * @return void
     */
    public function testLogin()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $result = json_decode((string) $response->getContent());
        $response->assertStatus(200)
            ->assertJson([
                'access_token' => $result->access_token,
                'refresh_token' => $result->refresh_token,
                'expires_in' => $result->expires_in,
            ]);
    }

    public function testLoginLocked()
    {
        $this->data['password'] = 'wrong';
        $i = 0;
        // force invalid password 5 times
        while ($i < config('auth.max_login_attempts')) {
            $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
            $i++;
        }
        // verify account is locked after 5 failed attempts
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'user_locked'
            ]);
    }

    public function testUserRepositoryUpdateLoginAttemptsMissingStatus()
    {
        $status = UserStatus::where('name', config('user.statuses.locked'))->firstOrFail();
        $status->update(['name' => 'LockedRenamed']);

        // lock user before testing
        User::updateOrCreate(
            ['email' => self::$user['email']],
            ['login_attempts' => 10]
        );

        $this->data['password'] = 'wrong';
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(500);

        // restore original status name
        $status->update(['name' => 'Locked']);
    }

    public function testLockedAccountWithCorrectPassword()
    {
        $this->data['password'] = 'wrong';
        $i = 0;
        // force invalid password 5 times
        while ($i <= config('auth.max_login_attempts')) {
            $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
            $i++;
        }
        // verify account is still locked even after using correct password
        $this->data['password'] = self::$password;
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'user_locked'
            ]);
    }
}
