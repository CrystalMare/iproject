<?php

GLOBAL $CONFIG, $DB;

DEFINE('img', 'images/auction');
DEFINE('inc', 'inc/');
DEFINE('pages', 'pages/');
DEFINE('index', 'index');

$CONFIG['sql']['server'] = "SVENLAPTOP";
$CONFIG['sql']['connection'] = array( "Database"=>"iproject");

$CONFIG['site'] = array(
    'host'              => 'localhost'
);

$CONFIG['mail'] = array(
    'host'              => 'smtp.gmail.com',
    'tls'               => true,
    'port'              => 587,
    'username'          => 'eenmaalandermaal@heaven-craft.net',
    'password'          => 'verkocht',
    'from'              => 'eenmaalandermaal@heaven-craft.net',
    'fullname'          => 'Eenmaal Andermaal'
);


function closeDB() {
    GLOBAL $DB;
    sqlsrv_close($DB);
}

function openDB() {
    GLOBAL $DB, $CONFIG;
    $DB = sqlsrv_connect($DB['sql']['server'], $CONFIG['sql']['connection']);
    if (!$DB){
        var_dump(sqlsrv_errors());
        die(\print_r("Fatal error", true));
    }
}