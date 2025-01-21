<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('location-updates', function ($user) {
    return $user !== null; // Ensures the user is authenticated
});

Broadcast::channel('reading-updates', function ($user) {
    return $user !== null;
});

Broadcast::channel('measurement-updates', function ($user) {
    return $user !== null;
});
