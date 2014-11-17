<?php

GLOBAL $DB;

$serverName = "SVENLAPTOP\SQLEXPRESS";
$connectionInfo = array( "Database"=>"iproject");

$DB = sqlsrv_connect(serverName, connectionInfo);

if (!$DB) die(\print_r(sqlsrv_errors(), true));

sqlsrv_close($DB);