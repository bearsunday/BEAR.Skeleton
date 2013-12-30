<?php
/**
 * @global $appDir
 */
namespace BEAR\Skeleton;

$sqlite = [
    'driver' => 'pdo_sqlite',
    'path' =>  $appDir . '/var/db/posts.sq3'
];

//$masterDbId = isset($_SERVER['MASTER_DB_ID']) ? $_SERVER['MASTER_DB_ID'] : 'root';
//$masterDbPassword = isset($_SERVER['MASTER_DB_PASSWORD']) ? $_SERVER['MASTER_DB_PASSWORD'] : '';
//$slaveDbId = isset($_SERVER['SLAVE_DB_ID']) ? $_SERVER['SLAVE_DB_ID'] : 'root';
//$slaveDbPassword = isset($_SERVER['SLAVE_DB_PASSWORD']) ? $_SERVER['SLAVE_DB_PASSWORD'] : '';
// $mysql = [
//    'master_db' => [
//        'driver' => 'pdo_mysql',
//        'host' => 'localhost',
//        'dbname' => 'blogbear',
//        'user' => $masterDbId,
//        'password' => $masterDbPassword,
//        'charset' => 'UTF8'
//    ],
//    'slave_db' => [
//        'driver' => 'pdo_mysql',
//        'host' => 'localhost',
//        'dbname' => 'blogbear',
//        'user' => $slaveDbId,
//        'password' => $slaveDbPassword,
//        'charset' => 'UTF8'
//    ]
//];

$config = [
    // database
    'master_db' => $sqlite,
    'slave_db' => $sqlite,
    // constants
    'app_name' => __NAMESPACE__,
    'tmp_dir' => "{$appDir}/var/tmp",
    'log_dir' => "{$appDir}/var/log",
    'lib_dir' => "{$appDir}/var/lib",
];

return $config;
