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
        $tsql = "SELECT looptijd FROM Looptijd WHERE actief = 1";
    }
}