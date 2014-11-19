<?php

GLOBAL $DB;

$serverName = "SVENLAPTOP\SQLEXPRESS";
$connectionInfo = array( "Database"=>"iproject");

$DB = sqlsrv_connect($serverName, $connectionInfo);

if (!$DB) die(\print_r(sqlsrv_errors(), true));

include('inc/class/ImageProvider.php');

sqlsrv_close($DB);