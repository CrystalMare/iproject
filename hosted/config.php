<?php

GLOBAL $CONFIG, $DB;

DEFINE('img', 'images/auction');
DEFINE('inc', 'inc/');
DEFINE('pages', 'pages/');
DEFINE('index', 'index');
DEFINE('uploads', 'upload/');

$CONFIG['sql'] = array(
    'host'              => 'mssql.iproject.icasites.nl',
    'connectioninfo'    => array(
    'Database'          => 'iproject5',
    'UID'               => 'iproject5',
    'PWD'               => '7ruchEbE'
    ));

$CONFIG['site'] = array(
    'host'              => 'localhost:8080/iproject/hosted'
);

$CONFIG['mail'] = array(
    'host'              => 'smtp.gmail.com',
    'tls'               => true,
    'port'              => 587,
    'username'          => 'eenmaalandermaal@heaven-craft.net',
    'password'          => 'verkocht2',
    'from'              => 'eenmaalandermaal@heaven-craft.net',
    'fullname'          => 'Eenmaal Andermaal'
);

function closeDB() {
    GLOBAL $DB;
    sqlsrv_close($DB);
}

function openDB() {
    GLOBAL $DB, $CONFIG;
    $DB = sqlsrv_connect($CONFIG['sql']['host'], $CONFIG['sql']['connectioninfo']);
    if (!$DB){
        var_dump(sqlsrv_errors());
        die(\print_r("Fatal error", true));
    }
}