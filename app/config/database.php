<?php

if ( ! defined('STARTED')) exit;

return [
    'donate' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'db' => 'donate'
    ],
    'servers' => [
        1 => [
            'title' => 'Server 1',
            'id' => 1,
            'login' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '',
                'db' => 'l2j_server'
            ],
            'game' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '',
                'db' => 'l2j_server'
            ]
        ]
    ]
];
