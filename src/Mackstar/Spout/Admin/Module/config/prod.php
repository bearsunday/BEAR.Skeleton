<?php

namespace Admin;

$id = isset($_SERVER['BEAR_DB_ID']) ? $_SERVER['BEAR_DB_ID'] : 'root';
$password = isset($_SERVER['BEAR_DB_PASSWORD']) ? $_SERVER['BEAR_DB_PASSWORD'] : '';

$slaveId = isset($_SERVER['BEAR_DB_ID_SLAVE']) ? $_SERVER['BEAR_DB_ID_SLAVE'] : 'root';
$slavePassword = isset($_SERVER['BEAR_DB_PASSWORD_SLAVE']) ? $_SERVER['BEAR_DB_PASSWORD_SLAVE'] : '';

$appDir = dirname(dirname(dirname(dirname(__DIR__))));
// @Named($key) => instance
$config = [
    // database
    'master_db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'blogbear',
        'user' => $id,
        'password' => $password,
        'charset' => 'UTF8'
    ],
    'slave_db' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'blogbear',
        'user' => $slaveId,
        'password' => $slavePassword,
        'charset' => 'UTF8'
    ],
    // constants
    'app_name' => __NAMESPACE__,
    'app_dir' => $appDir,
    'tmp_dir' => $appDir . '/var/tmp',
    'log_dir' => $appDir . '/var/log',
    'lib_dir' => $appDir . '/var/lib',
];

return $config;
