<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    | Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "ses", "log"
    */
    'driver' => env('MAIL_DRIVER', 'mail'),
    'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
    'port' => env('MAIL_PORT', 25),
    'from' => ['address' => 'no-reply@gmail.com', 'name' => 'Softtransfer'],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => true,
];
