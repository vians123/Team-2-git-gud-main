<?php

namespace Tests\Feature;

use Event;
use Tests\TestCase;
use App\Models\User;
use App\Events\NotificationCreated;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BroadcastNotificationTest extends TestCase
{
    /** @var array */
    private static $USER;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // set admin details
        self::$USER = User::find(1);
    }

    /**
     * BroadcastNotificationTest constructor.
     */
    public function __construct($name = 'BroadcastNotificationTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    /**
     * Testing if the Notification has been dispatched
     * using the Laravel Event class
     */
    public function testBroadcast(): void
    {
        Event::fake();

        $response = $this->actingAs(self::$USER, 'api')
                        ->json('POST', '/v1/notifications/test');
        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'broadcasted' => true,
                    ],
                ]);

        // verify if the Event has been dispatched properly
        Event::assertDispatched(NotificationCreated::class);
    }
}
