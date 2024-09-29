<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Authenticated if user is allowed to subscribe in this private channel
 *
 * @var string The channel name declared in your Event broadcastOn() method.
 * @var $user  The authenticated user.
 * @var $id    The {id} on the route e.g channel-name.{id}
 */
Broadcast::channel('user-notification.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
