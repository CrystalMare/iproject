<?php

GLOBAL $CONFIG, $DB;

DEFINE('img', 'images/auction');
DEFINE('inc', 'inc/');
DEFINE('pages', 'pages/');
DEFINE('index', 'index');

$CONFIG['db']['server'] = "SVENLAPTOP";
$CONFIG['db']['connection'] = array( "Database"=>"iproject");

function closeDB() {
    GLOBAL $DB;
    sqlsrv_close($DB);
}

function openDB() {
    GLOBAL $DB, $CONFIG;
    $DB = sqlsrv_connect($DB['db']['server'], $CONFIG['db']['connection']);
    if (!$DB){
        var_dump(sqlsrv_errors());
        die(\print_r("Fatal error", true));
    }
}