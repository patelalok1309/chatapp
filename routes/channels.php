<?php

use Illuminate\Support\Facades\Auth;
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

Broadcast::channel('status-update', function ($user) {
    return $user;
});
Broadcast::channel('broadcast-message', function ($user) {
    return $user;
});

Broadcast::channel('message-deleted' , function($user){
    return $user;
});

Broadcast::channel('message-updated', function($user){
    return $user;
});