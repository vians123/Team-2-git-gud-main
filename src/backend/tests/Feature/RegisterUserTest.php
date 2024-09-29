<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class RegisterUserTest extends TestCase
{
    /** @var array */
    private $data;

    /**
     * RegisterUserTest constructor.
     */
    public function __construct($name = 'RegisterUserTest')
    {
        parent::__construct($name);
        // test variables
        $this->data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@mail.com',
        ];
        $this->createApplication();
    }

    /**
     * Missing Parameter(s).
     */
    public function testRegisterMissingParam()
    {
        $data = $this->data;
        unset($data['email']);

        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $data);
        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'email' => [__('validation.required', ['attribute' => 'email'])],
                ]
            ]);
    }

    /**
     * Testing the Email format validation
     */
    public function testRegisterInvalidEmail()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', [
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'notAnEmail@',
                    ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'email' => [__('validation.regex', ['attribute' => 'email'])],
                ]
            ]);
    }

    /**
     * A successful account creation
     */
    public function testRegister()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $this->data);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => $this->data['first_name'],
                    'last_name' => $this->data['last_name'],
                    'email' => $this->data['email'],
                ]
            ]);
    }

    /**
     * Testing the event of registering existing user
     */
    public function testRegisterExistingUser()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $this->data);

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'email' => [__('validation.unique', ['attribute' => 'email'])],
                ]
            ]);
    }

    /**
     * Will Check if there is a token generated.
     */
    public function testEmailNotification()
    {
        $user = User::where('email', $this->data['email'])->first();
        // check if there is a token generated
        $this->assertDatabaseHas('activation_tokens', ['user_id' => $user->id]);

        // remove test data
        User::find($user->id)->delete();
    }
}
