<?php

require('../config.php');
openDB();
global $DB;

$info = array();
$tsql = "SELECT titel, voorwerpnummer FROM Voorwerp WHERE voorwerpnummer = ?;";
$stmt = sqlsrv_query($DB, $tsql, array($_GET['veiling']));
if (!$stmt) {
    $info['veilingnummer'] = $_GET['veiling'];
    $info['titel'] = "Bestaat niet!";
} else {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $info['voorwerpnummer'] = $row['voorwerpnummer'];
    $info['titel'] = $row['titel'];
}

header('Content-Type: application/json');
echo json_encode($info);
closeDB();
exit();