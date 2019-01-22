<?php

// MySQL config for OpenShift
$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
$dbName = getenv('OPENSHIFT_GEAR_NAME');
$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => "$dbHost",
    'port'     => "$dbPort",
    'dbname'   => "$dbName",
    'user'     => "$dbUser",
    'password' => "$dbPassword",
);