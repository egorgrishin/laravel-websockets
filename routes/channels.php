<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('online', fn (object $user) => [
    'id'       => $user->id,
    'nickname' => $user->nickname,
]);

Broadcast::channel('dialog.{firstId}.{secondUserId}', function (object $user, int $firstId, int $secondId) {
    if (!in_array($user->id, [$firstId, $secondId])) {
        return false;
    }

    return [
        'id'       => $user->id,
        'nickname' => $user->nickname,
    ];
});
