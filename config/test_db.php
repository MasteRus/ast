<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=' . env('DB_HOST_TEST', 'testdb') . ':' . env('DB_PORT_TEST', '3306')
    . ';dbname=' . env('DB_NAME_TEST', 'yii2basic_test');
$db['username'] = env('DB_USER_TEST', 'root');
$db['password'] = env('DB_PASS_TEST', 'password');

return $db;
