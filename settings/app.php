<?php

return [
    'base_url' => '',
    'css_theme' => 'lumen', // cosmo, darkly, lumen, paper, sandstone, simplex, slate, spacelab, superhero, united, yeti
    'timezone' => 'Europe/Vilnius',
    'email' => 'justas.asmanavicius@gmail.com',
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
        'enabled' => true
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
        'currency' => 'eur'
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
        'min_password' => 5,
    ],
    'language' => [
        'enabled' => [
            'lt', 'ru', 'gb', 'us'
        ]
    ]
]; 