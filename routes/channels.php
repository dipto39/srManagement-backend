<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum'], 'prefix' => 'api/']);
Broadcast::channel('test', function ($user, $id) {
    return true;
});

// ✅ Private channel
Broadcast::channel('private.test.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId; // only owner can listen
});

