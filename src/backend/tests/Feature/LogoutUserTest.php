<?php

namespace Tests\Feature;

use Hash;
use Tests\TestCase;
use App\Models\User;

class LogoutUserTest extends TestCase
{
    /** @var string */
    public $accessToken = null;

    /** @var array */
    public static $USER = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johndoe@mail.com',
        'password' => '',
        'user_status_id' => 1,
    ];

    /** @var string */
    private static $PASSWORD = 'password';

    /**
     * Execute before each test
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::$USER['password'] = Hash::make(self::$PASSWORD);
        User::updateOrCreate(
            ['email' => self::$USER['email']],
            self::$USER
        );
    }

    /**
     * Run after every test function.
     * @return void
     */
    public function tearDown(): void
    {
        // delete user
        $user = User::where('email', self::$USER['email'])->first();

        if ($user) {
            $user->delete();
        }

        parent::tearDown();
    }

    /**
     * LogoutUserTest constructor.
     * @return void
     */
    public function __construct($name = 'LogoutUserTest')
    {
        parent::__construct($name);
    }

    public function testLogoutWithoutToken()
    {
        $response = $this->json('DELETE', '/' . config('app.api_version') . '/oauth/token');
        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthenticated.',
            ]);
    }

    /**
     * Logout User
     * @return void
     */
    public function testLogout()
    {
        $response = $this->json(
            'POST',
            '/' . config('app.api_version') . '/oauth/token',
            [
                'client_id' => (int) config('app.client_id'),
                'client_secret' => config('app.client_secret'),
                'grant_type' => 'password',
                'username' => self::$USER['email'],
                'password' => self::$PASSWORD,
            ]
        );
        $result = json_decode((string) $response->getContent());
        $this->accessToken = $result->access_token;

        $response = $this->withHeaders([
                            'Authorization' => 'Bearer ' . $this->accessToken,
                        ])
                        ->json('DELETE', '/' . config('app.api_version') . '/oauth/token');
        $response->assertStatus(200)
            ->assertJson([
                'authenticated' => false,
            ]);
    }
}
