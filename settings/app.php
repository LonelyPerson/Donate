<?php

return [
    'base_url' => '',
    'css_theme' => 'lumen', // cosmo, darkly, lumen, paper, sandstone, simplex, slate, spacelab, superhero, united, yeti
    'timezone' => 'Europe/Vilnius',
    'registration' => [
        'enabled' => true,
        'min' => 4,
        'max' => 16
    ],
    'paypal' => [
        'price' => 1,
        'email' => 'justas.asmanavicius-merchant@gmail.com',
        'test' => true,
        'purpose' => 'Test server donate points',
        'min' => 1,
        'max' => 5
    ],
    'mokejimai' => [
        'id' => 45961,
        'secret' => '654784daf0b133e42d02214b22cb03a6',
        'test' => true,
        'min' => 1,
        'max' => 5,
        'price' => 1,
        'text' => 'Donate taskai',
        'version' => 1.6
    ],
    'captcha' => [
        'key' => '6Lf7lgATAAAAALOM3MwgHdFaaU8KzIwjvZQLb34x',
        'secret' => '6Lf7lgATAAAAALTriss9mruOYAzehs2KZ05awXeN',
        'login' => false,
        'registration' => false
    ],
    'history' => [
        'limit' => 20
    ],
    'language' => [
        'enabled' => [
            'lt', 'ru', 'gb', 'us'
        ]
    ]
]; 