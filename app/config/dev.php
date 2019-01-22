<?php

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => 'localhost',
    'port'     => '3306',
    'dbname'   => 'fayme',
    'user'     => 'adopteunpet_user',
    'password' => 'chaton',
);

// enable the debug mode
$app['debug'] = true;