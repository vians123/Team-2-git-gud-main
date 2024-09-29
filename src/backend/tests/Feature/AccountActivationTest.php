<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\ActivationToken;
use App\Services\API\UserService;
use App\Exceptions\ActivationTokenNotFoundException;

class AccountActivationTest extends TestCase
{
    /** @var Array */
    private static $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'John@test.com',
        'password' => '!p4ssW0rd',
        'role' => 'User',
    ];

    /** @var App\Models\User */
    private static $user;

    /** @var String*/
    private static $token;

    /** @var UserService */
    private static $service;

    /**
     * AccountActivationTest constructor.
     */
    public function __construct($name = 'AccountActivationTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$service = new UserService(new User());
        $status = UserStatus::where('name', 'Pending')->first();
        self::$data['user_status_id'] = $status->id;

        self::$data['email'] = uniqid() . '@testasdasd.com';
        // create user
        self::$user = self::$service->create(self::$data);
        // get the token
        self::$token = ActivationToken::where('user_id', self::$user->id)->first();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        // delete test account
        self::$user->delete();
    }

    /**
     * A successful account activation
     */
    public function testActivate()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/activate', [
            'token' => self::$token->token,
            'password' => 'Pass1234!',
            'password_confirmation' => 'Pass1234!',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'full_name' => self::$data['first_name'] . " " . self::$data['last_name'],
                    'first_name' => self::$data['first_name'],
                    'last_name' => self::$data['last_name'],
                    'email' => self::$data['email'],
                    'status' => 'Active'
                ]
            ]);
    }

    /**
     * Account that is already activated / Invalid Activation token.
     */
    public function testInvalidToken()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/activate', [
            'token' => self::$token->token,
            'password' => 'Pass1234!',
            'password_confirmation' => 'Pass1234!',
        ]);
        $response->assertStatus(500)
            ->assertJson([
                'error' => (new ActivationTokenNotFoundException())->getMessage()
            ]);
    }
}
