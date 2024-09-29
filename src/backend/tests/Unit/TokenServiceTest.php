<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ActivationToken;
use App\Models\PasswordResetToken;
use App\Services\API\TokenService;

class TokenServiceTest extends TestCase
{
    /** @var string */
    private static $TOKEN;

    /** @var App\Models\ActivationToken */
    private static $ACTIVATION_TOKEN;

    /** @var App\Models\PasswordResetToken */
    private static $RESET_TOKEN;

    /** @var App\Services\API\TokenService */
    protected $tokenService;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$TOKEN = md5(uniqid());

        // store test token data
        self::$ACTIVATION_TOKEN = ActivationToken::create(['token' => self::$TOKEN, 'user_id' => 1]);
        self::$RESET_TOKEN = PasswordResetToken::create(['token' => self::$TOKEN, 'email' => 'test@mail.com']);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        // delete test tokens
        self::$ACTIVATION_TOKEN->delete();
        self::$RESET_TOKEN->delete();
    }

    /**
     * TokenServiceTest constructor.
     *
     * @return void
     */
    public function __construct($name = 'TokenServiceTest')
    {
        parent::__construct($name);
        $this->createApplication();

        $this->tokenService = new TokenService(new ActivationToken(), new PasswordResetToken());
    }

    public function testVerifyMissingTypeField()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Missing required field type.');

        $this->tokenService->verify(['token' => self::$TOKEN]);
    }

    public function testVerifyMissingTokenField()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Missing required field token.');

        $this->tokenService->verify(['type' => 'activation']);
    }

    public function testVerifyInvalidType()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Invalid type, value must be either activation or reset.');

        $this->tokenService->verify(['type' => 'random', 'token' => self::$TOKEN]);
    }

    public function testVerifyNonExistingToken()
    {
        $this->expectException('Illuminate\Database\Eloquent\ModelNotFoundException');

        $this->tokenService->verify(['type' => 'activation', 'token' => 'non-existing']);
    }

    public function testVerifyActivationToken()
    {
        $token = $this->tokenService->verify(['type' => 'activation', 'token' => self::$TOKEN]);

        $this->assertTrue($token instanceof ActivationToken);
        $this->assertEquals($token->token, self::$TOKEN);
    }

    public function testVerifyPasswordResetToken()
    {
        $token = $this->tokenService->verify(['type' => 'password_reset', 'token' => self::$TOKEN]);

        $this->assertTrue($token instanceof PasswordResetToken);
        $this->assertEquals($token->token, self::$TOKEN);
    }
}
