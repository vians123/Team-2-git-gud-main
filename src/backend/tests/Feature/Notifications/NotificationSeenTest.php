<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationSeenTest extends TestCase
{
    /** @var array */
    private static $USER;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // set admin details
        self::$USER = User::find(1);

        Notification::factory()->count(30)->create([
            'recipient_id' => self::$USER->id,
            'type' => 'test_notification',
            'content' => 'A new user has signup.',
            'sender_id' => null,
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        Notification::where('recipient_id', self::$USER->id)
            ->where('type', 'test_notification')
            ->delete();
    }

    /**
     * NotificationSeenTest constructor.
     */
    public function __construct($name = 'NotificationSeenTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testMarkSeenNonExistingNotification()
    {
        $url  = sprintf('/%s/notifications/%s/seen', config('app.api_version'), 0);
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('PUT', $url);
        $response->assertStatus(500)
            ->assertJson([
                'error' => __('exception.not_found', ['model' => 'Notification']),
            ]);
    }

    public function testMarkNotificationSeen()
    {
        $notification = Notification::where('recipient_id', self::$USER->id)->first();
        $url  = sprintf('/%s/notifications/%s/seen', config('app.api_version'), $notification->id);
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('PUT', $url);
        $response->assertStatus(200)
                ->assertJson([ 'seen' => true ]);

        // check database
        $notification = Notification::where('recipient_id', self::$USER->id)->first();
        $this->assertNotNull($notification->read_at);
    }

    public function testMarkAllNotificationSeen()
    {
        $url  = sprintf('/%s/notifications/all/seen', config('app.api_version'));
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('PUT', $url);
        $response->assertStatus(200)
                ->assertJson([ 'seen' => true ]);

        // check database
        $notifications = Notification::where('recipient_id', self::$USER->id)->get();
        foreach ($notifications as $notification) {
            $this->assertNotNull($notification->read_at);
        }
    }
}
