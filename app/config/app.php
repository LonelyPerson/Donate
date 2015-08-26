<?php

return [
    'base_url' => '',
    'timezone' => 'Europe/Vilnius',
    'email' => 'justas.asmanavicius@gmail.com',
    'session' => 'simple', // simple/database - veiks tik po sistemos idiegimo
    'dev' => false,
    'player' => [
        'online_check' => false,
        'enabled' => true,
        'change_name' => [
            'enabled' => true,
            'min_chars' => 3,
            'max_chars' => 10,
            'allowed_chars' => 'a-zA-Z0-9_', // regexp
            'price' => 3
        ],
        'unstuck' => [
            'enabled' => true,
            'price' => 1,
            'loc' => 'x,y,z'
        ],
        'level' => [
            'enabled' => true,
            'allow_delevel' => true,
            'delevel_price' => 2, // kaina uz -1 lvl
            'price' => 1, // kaina uz +1 lvl
            'min_level' => 10,
            'max_level' => 80
        ]
    ],
    'registration' => [
        'enabled' => true,
        'min' => 4,
        'max' => 16
    ],
    'recovery' => [
        'enabled' => true,
        'in_menu' => true
    ],
    'shop' => [
        'enabled' => true,
        'buy_confirmation' => true,
        'per_page' => 0,
        'show_icons' => true,
        'auto_title' => true
    ],
    'inventory' => [
        'enabled' => true,
        'allow_delete' => true,
        'delete_confirm' => true
    ],
    'paypal' => [
        'enabled' => true,
        'price' => 1.23,
        'email' => 'justas.asmanavicius-merchant@gmail.com',
        'test' => true,
        'purpose' => 'Test server donate points',
        'min' => 1,
        'max' => 10,
        'currency' => 'eur'
    ],
    'mokejimai' => [
        'enabled' => true,
        'id' => 45961,
        'secret' => '654784daf0b133e42d02214b22cb03a6',
        'test' => true,
        'min' => 1,
        'max' => 5,
        'price' => 1,
        'text' => 'Donate taskai',
        'version' => 1.6,
        'currency' => 'eur',
        'verify_code' => '<meta name="verify-paysera" content="99456500740208784d3505b6bd1ccbc9">'
    ],
    'paygol' => [
        'enabled' => true,
        'id' => 328750,
        'min' => 1,
        'max' => 5,
        'price' => 1,
        'text' => 'Donate taskai',
        'currency' => 'eur'
    ],
    'captcha' => [
        'key' => '6Lf7lgATAAAAALOM3MwgHdFaaU8KzIwjvZQLb34x',
        'secret' => '6Lf7lgATAAAAALTriss9mruOYAzehs2KZ05awXeN',
        'login' => false,
        'registration' => false,
        'recovery' => false
    ],
    'sms' => [
        'paysera' => true,
    ],
    'history' => [
        'enabled' => true,
        'limit' => 20
    ],
    'settings' => [
        'enabled' => true,
        'min_password' => 5
    ],
    'mail' => [
        'type' => 'smtp',
        'from' => 'justas.asmanavicius@gmail.com',
        'from_name' => 'justas',
        'smtp' => [
            'host' => 'smtp.mandrillapp.com',
            'auth' => true,
            'username' => 'justas.asmanavicius@gmail.com',
            'password' => 'vCZ5diji9E9IembZ6_bpiw',
            'encryption' => 'tls', // tls / ssl
            'port' => 587
        ]
    ],
    'language' => [
        'enabled' => [
            'lt', 'ru', 'gb', 'us'
        ]
    ]
];
