<?php

namespace App\Broadcasters;

use Illuminate\Auth\GenericUser;
use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster as BasePusher;

class PusherBroadcaster extends BasePusher
{
    /**
     * Аутентификация входящего запроса для указанного канала.
     * Данный метод был переписан специально для того,
     * Чтобы позволить приложению работать без авторизации
     *
     * @noinspection PhpUndefinedFieldInspection
     */
    public function auth($request)
    {
        $request->setUserResolver(function () use ($request) {
            return new GenericUser([
                'id'       => $request->header('id'),
                'nickname' => $request->header('nickname'),
            ]);
        });
        $channelName = $this->normalizeChannelName($request->channel_name);
        return parent::verifyUserCanAccessChannel(
            $request, $channelName
        );
    }
}
