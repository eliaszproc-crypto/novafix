<?php
return [
    'app' => [
        'name'     => 'NovaFix',
        'url'      => 'https://novafix.pl',
        'debug'    => true,
    ],
    'db' => [
        'host'     => 'localhost',
        'name'     => 'novafix_db',
        'user'     => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],
    'mail' => [
        'host'     => 'smtp.hostido.pl',
        'port'     => 587,
        'user'     => 'serwis@novafix.pl',
        'password' => 'ZMIEN_HASLO',
        'from'     => 'serwis@novafix.pl',
        'from_name'=> 'NovaFix Serwis',
    ],
    'upload' => [
        'max_size' => 5 * 1024 * 1024,
        'allowed'  => ['jpg', 'jpeg', 'png', 'webp'],
        'path'     => ROOT_PATH . '/public/uploads/',
    ],
    'service_address' => [
        'name'    => 'NovaFix — Eliasz Proć',
        'street'  => 'ul. Przykładowa 1',
        'postal'  => '00-000',
        'city'    => 'Miasto',
        'phone'   => '+48 000 000 000',
        'email'   => 'serwis@novafix.pl',
    ],
];
