<?php

namespace Tests\Unit;

use Hash;
use Tests\TestCase;
use App\Models\User;
use App\Services\API\UserService;
use App\Models\PasswordResetToken;
use App\Services\API\PasswordService;

class PasswordServiceTest extends TestCase
{
    /** @var App\Services\API\PasswordService */
    protected $passwordService;

    /** @var string */
    protected static $TOKEN;

    /** @var App\Models\User */
    protected static $USER;

    /** @var string */
    protected static $PASSWORD = 'n3wp@ssw0rd';

    /** @var array */
    protected static $DATA = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'password' => '!p4ssW0rd',
        'role' => 'User',
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        // create the user temporarily
        self::$USER = (new UserService(new User()))->create(self::$DATA);
    }

    /**
     * PasswordServiceTest constructor.
     * @return void
     */
    public function __construct($name = 'PasswordServiceTest')
    {
        parent::__construct($name);
        $this->createApplication();

        $this->passwordService = new PasswordService(
            new PasswordResetToken(),
            new UserService(new User())
        );
    }

    public function testforgotWithInvalidEmail()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage(__('validation.regex', ['attribute' => 'email']));

        $this->passwordService->forgot('notAnEmail');
    }

    public function testforgotWithNonExistingUser()
    {
        $this->expectException('App\Exceptions\UserNotFoundException');
        $this->expectExceptionMessage(__('exception.not_found', ['model' => 'User']));

        $this->passwordService->forgot('me@test.com');
    }

    public function testForgotPassword()
    {
        $passwordResetToken = $this->passwordService->forgot(self::$DATA['email']);
        $this->assertTrue(($passwordResetToken instanceof PasswordResetToken));
        // get the reset token
        self::$TOKEN = $passwordResetToken->token;
    }

    public function testResetInvalidDataPassed()
    {
        $this->expectException('TypeError');

        $this->passwordService->reset('string');
    }

    public function testResetMissingTokenParam()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Missing required token field.');

        $this->passwordService->reset(['password' => 'a']);
    }

    public function testResetMissingPasswordParam()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Missing required password field.');

        $this->passwordService->reset(['token' => 'a']);
    }

    public function testResetInvalidExpiredToken()
    {
        $this->expectException('App\Exceptions\InvalidPasswordResetTokenException');
        $this->expectExceptionMessage(__('exception.invalid_password_reset_token'));

        $this->passwordService->reset([
            'token' => '12345adsfr1234',
            'password' => 'p@ssw0rd',
        ]);
    }

    public function testReset()
    {
        $user = $this->passwordService
                    ->reset([
                        'token' => self::$TOKEN,
                        'password' => self::$PASSWORD,
                    ]);

        $this->assertTrue($user instanceof User);

        // verify password is updated
        $this->assertTrue(Hash::check(self::$PASSWORD, $user->password));

        // delete user after test
        self::$USER->delete();
    }
}
