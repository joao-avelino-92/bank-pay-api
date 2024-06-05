<?php

    return [
        'defaults' => [
            'guard'     => 'api',
            'passwords' => 'users',
        ],

        'guards' => [
            'user' => [
                'driver'   => 'passport',
                'provider' => 'user',
            ],
            'api' => [
                'driver'   => 'passport',
                'provider' => 'user',
            ],
        ],

        'providers' => [
            'user' => [
                'driver' => 'eloquent',
                'model'  => \App\Models\User::class
            ]
        ]
    ];
