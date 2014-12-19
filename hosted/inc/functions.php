<?php

function isSeller($username)
{
    global $DB;
    $sql = "SELECT verkoper FROM Gebruiker WHERE gebruikersnaam = ?;";
    $params = array($username);
    $stmt = sqlsrv_query($DB, $sql, $params);
    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row['verkoper'];
}

class DatabaseTools {

    static function getAuctionDurations() {
        global $DB;
        $tsql = "SELECT looptijd FROM Looptijd WHERE actief = 1 ORDER BY looptijd ASC";
        $stmt = sqlsrv_query($DB, $tsql);
        if (!$stmt) {
            die(print_r(sqlsrv_errors()));
        }
        $durations = array();
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            array_push($durations, $row['looptijd']);
        }
        return $durations;
    }

    static function getCountries() {
        global $DB;
        $sql = "SELECT landnaam FROM Land ORDER BY landnaam ASC";
        $stmt = sqlsrv_query($DB, $sql);
        if (!$stmt) {
            die(print_r(sqlsrv_errors()));
        }
        $countries = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            array_push($countries, $row['landnaam']);
        }
     return $countries;
    }
}