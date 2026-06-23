<?php

return [
    'expiration_time' => 10 * 60, // in seconds
    'load_migrations' => true,
    'models' => [
        'user' => App\Models\User::class,
    ],
];
