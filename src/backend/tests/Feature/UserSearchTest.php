<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class UserSearchTest extends TestCase
{
    /** @var array */
    private static $ADMIN;

    /** @var string */
    private static $KEYWORD = 'ad';

    /** @var array */
    private static $USERIDS = [];

    /** @var int */
    private static $TOTAL;

    /** @var int */
    private static $LASTPAGE;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // set admin details
        self::$ADMIN = User::find(1);
    }

    /**
     * UserSearchTest constructor.
     */
    public function __construct($name = 'UserSearchTest')
    {
        parent::__construct($name);
        $this->createApplication();
    }

    public function testSearchNoResults()
    {
        $response = $this->actingAs(self::$ADMIN, 'api')
                        ->json('GET', '/' . config('app.api_version') . '/users?keyword=randomString');
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
        $response = $this->actingAs(self::$ADMIN, 'api')
                    ->json('GET', '/' . config('app.api_version') . '/users?keyword=' . self::$KEYWORD);
        $response->assertStatus(200);
        $result = json_decode((string) $response->getContent());

        // verify if the keyword exists either in first name, last name or email
        foreach ($result->data as $user) {
            $hasKeyword = false;

            if (strpos(strtolower($user->first_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->last_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->email), self::$KEYWORD) !== false) {
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

    public function testSearchWithLimit()
    {
        $limit = floor(self::$TOTAL / 2) ?: 1;

        // initialize query
        $query = [
            'limit' => $limit,
            'keyword' => self::$KEYWORD,
        ];

        $response = $this->actingAs(self::$ADMIN, 'api')
                    ->json('GET', '/' . config('app.api_version') . '/users?' . http_build_query($query));
        $result = $response->getData();
        $response->assertStatus(200)
            ->assertJsonPath('meta.perPage', intval($limit)) // check specific key only
            ->assertJsonCount($limit, 'data'); // verify limit matches the data per page

        // store last page for next test
        self::$LASTPAGE = $result->meta->lastPage;
    }

    public function testSearchByPage()
    {
        $limit = floor(self::$TOTAL / 2) ?: 1;
        $page = 1;

        // initialize query
        $query = [
            'limit' => $limit,
            'keyword' => self::$KEYWORD,
            'page' => $page,
        ];

        $response = $this->actingAs(self::$ADMIN, 'api')
                ->json('GET', '/' . config('app.api_version') . '/users?' . http_build_query($query));
        $result = $response->getData();
        $response->assertStatus(200)
            ->assertJsonPath('meta.currentPage', $page) // check specific key only
            ->assertJsonCount(count($result->data), 'data');
    }
}
