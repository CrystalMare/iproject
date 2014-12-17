<?php
require('../config.php');
openDB();
global $DB;

$cat = -1;

if (!isset($_GET['cat'])) {
    $cat = -1;
} else {
    $cat = $_GET['cat'];
}

$categorie = array();

if ($cat == -1) {
    $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek IS NULL ORDER BY volgnummer, rubrieknaam;";
    $stmt = sqlsrv_query($DB, $sql, array());
} else {
    $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek = ? ORDER BY volgnummer, rubrieknaam;";
    $stmt = sqlsrv_query($DB, $sql, array($cat));
}

if (!$stmt) {
    die(print_r(sqlsrv_errors()));
}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $categorie[$row['rubrieknummer']] = array (
        "rubrieknaam" => $row['rubrieknaam'],
        "rubrieknummer" => $row['rubrieknummer'],
        "ouderrubriek" => $row['ouderrubriek'],
        "volgnummer" => $row['volgnummer']
    );
}

header("Content-Type: application/json");
echo json_encode($categorie);
closeDB();
exit();