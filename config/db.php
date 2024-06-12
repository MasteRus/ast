<?php

use yii\db\Connection;

return [
    'class'    => Connection::class,
    'dsn'      =>
        'mysql:host=' . env('DB_HOST', 'db') . ':' . env('DB_PORT', '3306')
        . ';dbname=' . env('DB_NAME','root'),
    'username' => env('DB_USER', 'root'),
    'password' => env('DB_PASS', 'password'),
    'charset'  => 'utf8',
];
