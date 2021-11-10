<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'CLIENT_API' => 'https://www.recaptcha.net/recaptcha/api.js',
    'VERIFY_URL' => 'https://www.recaptcha.net/recaptcha/api/siteverify',
    'options' => [
        'timeout' => 30,
    ],
];
