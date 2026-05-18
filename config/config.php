<?php
return [
    'app' => [
        'name'     => 'NovaFix',
        'url'      => 'https://novafix.pl',
        'debug'    => false,
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
    'payment' => [
        'bank_account'  => 'PL XX XXXX XXXX XXXX XXXX XXXX XXXX', // Uzupełnij swój numer konta
        'bank_name'     => 'Eliasz Proć — NovaFix',
        'blik_phone'    => '691 113 754',
    ],
    'service_address' => [
        'name'    => 'Eliasz Proć — NovaFix',
        'street'  => 'ul. Wyszyńskiego 14a/1',
        'postal'  => '78-400',
        'city'    => 'Szczecinek',
        'phone'   => '691 113 754',
        'email'   => 'eliasz.proc@gmail.com',
    ],
    'service_parcel' => [
        'name'    => 'NovaFix (paczkomat)',
        'locker'  => 'SCZ04M',
        'postal'  => '78-400',
        'city'    => 'Szczecinek',
    ],
];
