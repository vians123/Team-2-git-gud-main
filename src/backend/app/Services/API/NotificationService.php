<?php

namespace App\Services\API;

use Exception;
use Carbon\Carbon;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;

class NotificationService
{
    /** @var App\Models\Notification */
    protected $notification;

    /**
     * NotificationService constructor.
     *
     * @params App\Models\Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Retrieves all notifications of current logged in user.
     *
     * @param array $conditions
     * @return array
     */
    public function search(array $conditions): array
    {
        // default to 1 if page not provided
        $page = 1;
        $limit = config('search.results_per_page');
        $unread = 0;

        if (array_key_exists('page', $conditions) === true) {
            $page = $conditions['page'];
        }

        if (array_key_exists('limit', $conditions) === true) {
            $limit = $conditions['limit'];
        }

        $skip = ($page > 1) ? ($page * $limit - $limit) : 0;

        // initialize query
        $query = $this->notification;

        if (array_key_exists('recipient_id', $conditions)) {
            $query = $query->where('recipient_id', $conditions['recipient_id']);
            $unreadNotifications = $this->notification
                                        ->whereNull('read_at')
                                        ->where('recipient_id', $conditions['recipient_id'])
                                        ->get();
            $unread = $unreadNotifications->count();
        }

        // perform user search
        $results = $query->skip($skip)
                        ->orderBy($conditions['sort'], $conditions['order'])
                        ->paginate($limit);

        $urlParams = http_build_query(['keyword' => $conditions['keyword'], 'limit' => $limit]);
        $firstPageUrl = $results->url(1) . '&' . $urlParams;
        $nextPageUrl = ($results->nextPageUrl()) ? $results->nextPageUrl() . '&' . $urlParams : null;
        $previousPageUrl = ($results->previousPageUrl()) ? $results->previousPageUrl() . '&' . $urlParams : null;

        return [
            'data' => NotificationResource::collection($results),
            'unread' => $unread,
            'meta' => [
                'total' => $results->total(),
                'currentPage' => $page,
                'lastPage' => $results->lastPage(),
                'perPage' => $results->perPage(),
                'firstPageUrl' => $firstPageUrl,
                'previousPageUrl' => $previousPageUrl,
                'nextPageUrl' => $nextPageUrl,
                'url' => $results->url($page) . '&' . $urlParams,
            ],
        ];
    }

    /**
     * Adds current date time value to read_at field
     * to indicate notification is already seen by the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function seen(string $id, int $user_id): bool
    {
        // initialize query
        $query = $this->notification->where('recipient_id', $user_id);

        if ('all' !== $id) {
            $query = $query->where('id', $id);
        }

        // perform update
        $count = $query->update(['read_at' => Carbon::now()]);

        if (0 === $count) {
            throw new Exception(__('exception.not_found', ['model' => 'Notification']));
        }

        return true;
    }
}
