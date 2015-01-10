<?php

setDefaultBuffer();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        get();
        break;
    case 'POST':
        post();
        break;
    default:
        get();
}

function setDefaultBuffer() {
    global $buffer;

    $buffer['startprijs'] = $_SESSION['startprijs'];
    $buffer['auctionid'] = $_SESSION['auctionid'];
    $buffer['artikelnaam'] = $_SESSION['artikelnaam'];
    $buffer['beschrijving'] = $_SESSION['beschrijving'];
    $buffer['looptijd'] = $_SESSION['looptijd'];
    $buffer['betalingswijze'] = $_SESSION['betalingswijze'];
    $buffer['land'] = $_SESSION['land'];
    $buffer['betalingsinstructies'] = $_SESSION['betalingsinstructies'];
    $buffer['locatie'] = $_SESSION['locatie'];
    $buffer['verzendkosten'] = $_SESSION['verzendkosten'];
    $buffer['verzendinstructies'] = $_SESSION['verzendinstructies'];
    $buffer['verkoper'] = $_SESSION['username'];
}

function get() {
    global $buffer;

}

function post()
{
    global $buffer, $DB;
    //var_dump($_POST);
    //var_dump($_FILES);
    //var_dump($_SESSION);

    //getUploadedFiles(1);
    /*
        $SQL = "SET IDENTITY_INSERT voorwerp OFF;";
        $stmt = sqlsrv_query($DB, $SQL);
        if(!$stmt)
        {
            $buffer['error'] = "IDENTITY INSERT GONE WRONG";
        }
    */

    $tsql = "INSERT INTO Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam,
                                   land, looptijd, verzendkosten, verzendinstructies, verkoper) OUTPUT inserted.voorwerpnummer
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
    $params = array(
        //$_SESSION['auctionid'],
        $_SESSION['artikelnaam'],
        $_SESSION['beschrijving'],
        $_SESSION['startprijs'],
        $_SESSION['betalingswijze'],
        $_SESSION['betalingsinstructies'],
        $_SESSION['locatie'],
        $_SESSION['land'],
        $_SESSION['looptijd'],
        $_SESSION['verzendkosten'],
        $_SESSION['verzendinstructies'],
        $_SESSION['username']
    );

    $stmt = sqlsrv_query($DB, $tsql, $params);
    if (!$stmt) {
        var_dump(sqlsrv_errors());
        $error = "Er is iets misgegaan. Probeer het opnieuw.";
        $buffer['error'] = $error;
        exit();
    }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    //var_dump($row);
    $auction = $row['voorwerpnummer'];

    // Add auction to category
    $tsql = "INSERT INTO Voorwerpinrubriek (voorwerpnummer, rubrieknumer)
             VALUES (?, ?);";
    $params = array(
        $auction,
        $_SESSION['category1']
    );
    sqlsrv_query($DB, $tsql, $params);

    if (isset($_SESSION['category2']))
    {
        $tsql = "INSERT INTO Voorwerpinrubriek (voorwerpnummer, rubrieknummer)
                 VALUES (?, ?);";
        $params = array(
            $auction,
            $_SESSION['category2']
        );
        sqlsrv_query($DB, $tsql, $params);
    }
    //now the files need to be retrieved from the temporary table 'tijdelijkBestand'
    convertFilesToJPGAndSave(getUploadedFiles($auction), $auction);

    header("Location: index.php?page=mijnveilingen");

}

function getUploadedFiles($auction)
{
    $validfiles = copyFiles($auction);
    var_dump($validfiles);
    var_dump($_FILES);
    foreach ($validfiles['filenaam'] as $key => $value) {
        if ($value['size'] > 2000000)
            continue;
        if ($value['name'] != "" && ($value['type'] == "image/png" || $value['type'] == "image/jpg" || $value['type'] == "image/jpeg")) {
            array_push($validfiles, $value);
        }
    }
    return $validfiles;
}
function copyFiles($voorwerpnummer)
{
    global $DB;

    $tsql = "SELECT *
             FROM tijdelijkBestand
             WHERE voorwerpnummer = ?;";
    $params = array($voorwerpnummer);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    return $result;
}
function convertFilesToJPGAndSave($files, $auction) {
    global $DB;
    $count = 0;
    $filename = "";
    $auctionid = $auction;
    $tsql = "INSERT INTO Bestand (filenaam, voorwerpnummer) VALUES (?, ?)";
    $ps = sqlsrv_prepare($DB, $tsql, array (&$filename, &$auctionid));
    var_dump(sqlsrv_errors());
    foreach($_FILES as $key => $value) {
        $filename = uploads . "auction_" . $auction . "_$count" . ".jpg";
        if ($value['type'] == "image/jpg" || $value['type'] == "image/jpeg") {
            copy($value['tmp_name'], $filename);
        } else {
            $image = imagecreatefrompng($value['tmp_name']);
            imagejpeg($image, $filename);
        }
        sqlsrv_execute($ps);
        var_dump(sqlsrv_errors());
        $count++;
    }
}

