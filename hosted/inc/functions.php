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

function checkLuhn($number) {
    settype($number, 'string');
    $number = preg_replace("/[^0-9]/", "", $number);
    $sumTable = array(
        array(0,1,2,3,4,5,6,7,8,9),
        array(0,2,4,6,8,1,3,5,7,9));
    $sum = 0;
    $flip = 0;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $sum += $sumTable[$flip++ & 0x1][$number[$i]];
    }
    return $sum % 10 === 0;
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

    static function getBeoordeling($user) {
        global $DB;
        $tsql = <<<END
SELECT COUNT(*) AS hoeveelheid, * FROM (
  SELECT feedbacktype FROM Voorwerp
    LEFT JOIN Feedback ON
                         Voorwerp.voorwerpnummer = Feedback.voorwerpnummer AND gebruikersoort = 'verkoper'
  WHERE koper = ?
  UNION ALL
  SELECT feedbacktype FROM Voorwerp
    LEFT JOIN Feedback ON
                         Voorwerp.voorwerpnummer = Feedback.voorwerpnummer AND gebruikersoort = 'koper'
  WHERE verkoper = ?
) AS iets GROUP BY feedbacktype;
END;
        $stmt = sqlsrv_query($DB, $tsql, array($user, $user));
        if (!$stmt)
            return null;

        $positief = 0;
        $negatief = 0;
        $neutraal = 0;

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            switch ($row['feedbacktype']) {
                case 'positief':
                    $positief += $row['hoeveelheid'];
                    continue;
                case 'negatief':
                    $negatief += $row['hoeveelheid'];
                    continue;
                case 'neutraal':
                    $neutraal += $row['hoeveelheid'];
            }
        }
        $score = 0.0;
        $score += ($positief * 5);
        $score += ($negatief * 1);
        $score += ($neutraal * 3);

        return ($positief + $neutraal + $negatief) == 0 ? 0 : $score / ($positief + $neutraal + $negatief);
    }

    static function getBeoordelingStars($user) {
        static $grijsimg = '<img src="img/grijs.png" alt="negatief" />';
        static $goudimg = '<img src="img/goud.png" alt="negatief" />';
        $result = "";
        $beoordeling = DatabaseTools::getBeoordeling($user);
        $beoordeling = round($beoordeling);
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $beoordeling)
                $result .= $goudimg;
            else
                $result .= $grijsimg;
        }
        return $result;
    }
}