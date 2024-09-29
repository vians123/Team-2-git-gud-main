<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\App;
use App\Exceptions\UserLockedException;
use App\Exceptions\UserPendingException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserNotCreatedException;
use App\Exceptions\AuthModelNotSetException;
use App\Exceptions\UserStatusNotFoundException;
use App\Exceptions\InvalidUserPasswordException;
use App\Exceptions\InvalidUserCredentialsException;
use App\Exceptions\ActivationTokenNotFoundException;
use App\Exceptions\InvalidPasswordResetTokenException;

class LocalizationTest extends TestCase
{
    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        // reset localization
        App::setLocale('en');
    }

    /**
     * LocalizationTest constructor.
     */
    public function __construct($name = 'LocalizationTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testCustomExceptionJaLocale()
    {
        $exceptions = [
            ['class' => ActivationTokenNotFoundException::class , 'message' => '無効/期限切れのアクティベーショントークンです。'],
            ['class' => AuthModelNotSetException::class , 'message' => 'Auth Modelが設定されていません。'],
            ['class' => InvalidPasswordResetTokenException::class , 'message' => '期限切れのパスワードリセットトークン。'],
            ['class' => InvalidUserCredentialsException::class , 'message' => '無効なユーザー資格情報です。'],
            ['class' => InvalidUserPasswordException::class , 'message' => 'ユーザーパスワードが無効です。'],
            ['class' => UserLockedException::class , 'message' => 'アカウントがロックアウトされました。パスワードをリセットしてください。'],
            ['class' => UserNotCreatedException::class , 'message' => 'ユーザーを作成できません。'],
            ['class' => UserNotFoundException::class , 'message' => 'ユーザーを取得できません。'],
            ['class' => UserPendingException::class , 'message' => '電子メールは確認されていません。'],
            ['class' => UserStatusNotFoundException::class , 'message' => 'ステータスの取得ができません。'],
        ];

        App::setLocale('ja');

        foreach ($exceptions as $exception) {
            $this->expectException($exception['class']);
            $this->expectExceptionMessage($exception['message']);
            throw new $exception['class']();
        }
    }

    public function testFormRequestValidationJaLocale()
    {
        $response = $this->json(
            'POST',
            '/' . config('app.api_version') . '/register',
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ]
        );

        $response->assertStatus(422)
            ->assertJson([
                'error' => [
                    'email' => [__('validation.required', ['attribute' => 'email'])],
                ]
            ]);
    }
}
