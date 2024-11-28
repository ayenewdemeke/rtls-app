<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('location-updates', function ($user) {
    return auth()->check(); // Allows only authenticated users to listen
});

Broadcast::channel('bus-location-updates', function ($user) {
    return auth()->check(); // Allows only authenticated users to listen
});
