<?php

namespace Tests\Feature\Notifications;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationSearchTest extends TestCase
{
    /** @var array */
    private static $USER;

    /** @var int */
    private static $TOTAL;

    /** @var int */
    private static $LASTPAGE;

    /** @var string */
    private static $KEYWORD = 'signup';

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
     * NotificationSearchTest constructor.
     */
    public function __construct($name = 'NotificationSearchTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testSearchNoResults()
    {
        $url  = sprintf('/%s/notifications?keyword=randomString', config('app.api_version'));
        $response = $this->actingAs(self::$USER, 'api')
                        ->json('GET', $url);
        $result = $response->getData();
        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'meta' => [
                    'total' => $result->meta->total,
                    'lastPage' => $result->meta->lastPage,
                    'previousPageUrl' => $result->meta->previousPageUrl,
                    'nextPageUrl' => $result->meta->nextPageUrl,
                ]
            ]);
    }

    public function testSearchByKeyword()
    {
        $url  = sprintf('/%s/notifications?keyword=%s', config('app.api_version'), self::$KEYWORD);
        $response = $this->actingAs(self::$USER, 'api')
                    ->json('GET', $url);
        $response->assertStatus(200);
        $result = json_decode((string) $response->getContent());

        // verify if the keyword exists either in first name, last name or email
        foreach ($result->data as $notification) {
            $hasKeyword = false;

            if (strpos(strtolower($notification->content), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            $this->assertTrue($hasKeyword);
        }

        // verify by default starting page is 1
        $this->assertEquals(1, $result->meta->currentPage);

        // verify default search limit
        $this->assertEquals(config('search.results_per_page'), $result->meta->perPage);

        // store total for limit testing
        self::$TOTAL = $result->meta->total;
    }

    public function testSearchByPage()
    {
        $page = 2;
        // initialize query
        $query = [ 'page' => $page ];
        $url  = sprintf('/%s/notifications?%s', config('app.api_version'), http_build_query($query));
        $response = $this->actingAs(self::$USER, 'api')
                    ->json('GET', $url);
        $result = $response->getData();
        $response->assertStatus(200)
            ->assertJsonPath('meta.currentPage', $page) // check specific key only
            ->assertJsonCount(count($result->data), 'data');
    }
}
