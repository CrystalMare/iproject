<?php
require('../config.php');
require('Category.php');
openDB();
global $DB;

$tsql = "SELECT beschrijving FROM Voorwerp WHERE voorwerpnummer = ?;";
$stmt = sqlsrv_query($DB, $tsql, array(isset($_GET['veiling']) ? $_GET['veiling'] : -1));
if (!$stmt || !sqlsrv_has_rows($stmt)) {
    exit("not found");
}


echo sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['beschrijving'];
closeDB();
exit();