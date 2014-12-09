<?php

GLOBAL $CONFIG, $DB;

DEFINE('images', 'images/auction');
DEFINE('include', 'inc/');
DEFINE('pages', 'pages/');
DEFINE('index', 'index');

$CONFIG['db']['server'] = "SVENLAPTOP\\SQLEXPRESS";
$CONFIG['db']['connection'] = array( "Database"=>"iproject");

function closeDB() {
    GLOBAL $DB;
    sqlsrv_close($DB);
}

function openDB() {
    GLOBAL $DB, $CONFIG;
    $DB = sqlsrv_connect($CONFIG['server'], $CONFIG['connection']);
    if (!$DB) die(\print_r(sqlsrv_errors(), true));
}