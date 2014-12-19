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

}

function get() {
    global $buffer;

}

function post() {
    global $buffer, $DB;
    var_dump($_POST);
    var_dump($_FILES);

    $error = "";

    //Validation
    if (strlen($_POST['artikelnaam']) < 3)
        $error = "Artikelnaam moet minstens drie tekens lang zijn.";
    if (strlen($_POST['beschrijving']) < 3)
        $error = "Beschrijving moet minstens drie tekens lang zijn";
    $startprijs = $_POST['startprijs'];
    if (!settype($startprijs, "float")) {
        $error = "Voer een geldige startprijs in.";
    } else {
        if ($startprijs < 1.00 || $startprijs > 90000.00) {
            $error = "Startprijs moet tussen de &#8364;1,- en de &#8364;90000.00,- liggen.";
        }
    }
    $verzendkosten = $_POST['verzendkosten'] == "" ? 0.00 : $_POST['verzendkosten'];
    if (!settype($verzendkosten, "float")) {
        $error = "Voor een geldige verzend prijs in.";
    }
    $betalingsmethode = "";
    if ($_POST['betalingswijze'] == "")
        $betalingsmethode == "Bank/Giro";

    //Files
    if (count(getUploadedFiles()) < 1) {
        $error = "U moet minstens 1 afbeelding uploaden";
    }

    if ($error != "") {
        $buffer['error'] = $error;
        return;
    }


    $tsql = "INSERT INTO Voorwerp (titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam,
            land, looptijd, verzendkosten, verzendinstructies, verkoper) OUTPUT inserted.voorwerpnummer
             VALUES (?,?,?,?,?,?,?,?,?,?,?) ";
    $params = array(
        $_POST['artikelnaam'],
        $_POST['beschrijving'],
        $startprijs,
        $betalingsmethode,
        $_POST['betalingsinstructie'],
        $_POST['locatie'],
        $_POST['land'],
        $_POST['looptijd'],
        $verzendkosten,
        $_POST['verzendinstructies'],
        $_SESSION['username']
    );
    $error = "";

    $stmt = sqlsrv_query($DB, $tsql, $params);
    if (!$stmt) {
        var_dump(sqlsrv_errors());
        $error = "Er is iets mis gaan. Probeer het opnieuw.";
        $buffer['error'] = $error;
    }
    $auction = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['voorwerpnummer'];

    convertFilesToJPGAndSave(getUploadedFiles(), $auction);
}

function getUploadedFiles()
{
    $validfiles = array();
    foreach ($_FILES as $key => $value) {
        if ($value['size'] > 2000000)
            continue;
        if ($value['name'] != "" && ($value['type'] == "image/png" || $value['type'] == "image/jpg")) {
            $validfiles[$key] = $value;
        }
    }
    return $validfiles;
}

function convertFilesToJPGAndSave($files, $auction) {
    global $DB;
    $count = 0;
    $filename = "";
    $auctionid = $auction;
    $tsql = "INSERT INTO Bestand (filenaam, voorwerpnummer) VALUES (?, ?)";
    $ps = sqlsrv_prepare($DB, $tsql, array (&$filename, &$auctionid));
    mkdir(uploads . $auction);
    foreach($files as $key => $value) {
        if ($value['type'] == "image/jpg") {
            $image = imagecreatefromjpeg($value['tmp_name']);
            imagejpeg($image, uploads . $auction . "/auction_" . $auction . "_$count" . ".jpg");
        } else {
            $image = imagecreatefrompng($value['tmp_name']);
            imagejpeg($image, uploads . $auction . "/auction_" . $auction . "_$count" . ".jpg");
        }
        $filename = "auction_" . $auction . "_$count" . ".jpg";
        sqlsrv_execute($ps);
        $count++;

    }
}

