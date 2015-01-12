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
    $buffer['looptijden'] = "";
    $buffer['countries'] = "";
    $buffer['error'] = "";
}

function get() {
    global $buffer;
    if (!isset($_SESSION['category1'])) {
        header('Location: index.php?page=productveilen');
        //exit();
    }

    //Looptijd
    foreach (DatabaseTools::getAuctionDurations() as $value) {
        $buffer['looptijden'] .= "<option value='$value'>$value</option>";
    }
    unset($value);
    //Landen
    foreach (DatabaseTools::getCountries() as $value) {
        if ($value == "Netherlands") {
            $buffer['countries'] .= "<option selected='selected' value='$value'>$value</option>";
        } else {
            $buffer['countries'] .= "<option value='$value'>$value</option>";
        }
    }
}

function post() {
    global $buffer, $DB;
    $error = "";
    $isError = false;

    if (!isset($_SESSION['category1']) || $_SESSION['category1'] == "") {
        if (($high1 = getHigh1()) == null) {
            header('Location: index.php?page=productveilen');
            exit();
        }
        if ($_POST["categorieaantal"] == 2) {
            $_SESSION['category2'] = getHigh2();
        }
        $_SESSION['category1'] = $high1;
    }


    //header('Location: index.php?page=productplaatsen');
    //exit();

if(isset($_POST['artikelnaam'])) {
    var_dump($_POST);
    if (!checkTitel($_POST['artikelnaam'])) {
        $error = "Artikelnaam moet minstens drie tekens lang zijn";
        $isError = true;
    }
    if (!checkBeschrijving($_POST['beschrijving'])) {
        $error = "Beschrijving moet minstens drie tekens lang zijn";
        $isError = true;
    }
    if (checkStartprijs($_POST['startprijs']) != "") {
        $error = checkStartprijs($_POST['startprijs']);
        $isError = true;
    }
    $_POST['verzendkosten'] = $_POST['verzendkosten'] == "" ? 0.00 : $_POST['verzendkosten'];
    if (!checkVerzendkosten($_POST['verzendkosten'])) {
        $error = "Voer een geldige verzendprijs in";
        $isError = true;
    }
    if ($_POST['betalingswijze'] == "") {
        $_POST['betalingswijze'] = "Bank/Giro";
    }
    if (count(getUploadedFiles()) < 1) {
        $error = "U moet minstens 1 afbeelding uploaden";
        $isError = true;
    }


    if (!$isError) {
        $auction = getNextVoorwerpnummer();
        $_SESSION['auctionid'] = $auction;
        $_SESSION['artikelnaam'] = $_POST['artikelnaam'];
        $_SESSION['beschrijving'] = $_POST['beschrijving'];
        $_SESSION['startprijs'] = $_POST['startprijs'];
        $_SESSION['looptijd'] = $_POST['looptijd'];
        $_SESSION['betalingswijze'] = $_POST['betalingswijze'];
        $_SESSION['land'] = $_POST['land'];
        $_SESSION['betalingsinstructies'] = $_POST['betalingsinstructies'];
        $_SESSION['locatie'] = $_POST['locatie'];
        $_SESSION['verzendkosten'] = $_POST['verzendkosten'];
        $_SESSION['verzendinstructies'] = $_POST['verzendinstructies'];
        convertFilesToJPGAndSave(getUploadedFiles(), $auction);
        header("Location: index.php?page=productnacontrole");
    } else {
        $buffer['error'] = $error;
    }
    get();
}
    else {
        $buffer['error'] = "Niet alle onderdelen zijn ingevuld.";
    }
    get();
    
}
function convertFilesToJPGAndSave($files, $auction) {
    global $DB;
    $count = 0;
    $filename = "";
    $auctionid = $auction;
    $tsql = "INSERT INTO tijdelijkBestand (filenaam, voorwerpnummer) VALUES (?, ?)";
    $ps = sqlsrv_prepare($DB, $tsql, array (&$filename, &$auctionid));
    var_dump(sqlsrv_errors());
    foreach($files as $key => $value) {
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
function getNextVoorwerpnummer()
{
    global $DB;
    $tsql = "SELECT MAX(voorwerpnummer) AS voorwerpnummer FROM voorwerp;";
    $stmt = sqlsrv_query($DB, $tsql);
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $result['voorwerpnummer'] + 1;
}
function checkVerzendkosten($verzendkosten)
{
    if (!settype($verzendkosten, "float")) {
        return false;
    }
    return true;
}
function checkStartprijs($startprijs) {
    if (!settype($startprijs, "float")) {
        $error = "Voer een geldige startprijs in.";
        return $error;
    } else {
        if ($startprijs < 1.00 || $startprijs > 90000.00) {
            $error = "Startprijs moet tussen de &#8364;1,- en de &#8364;90000.00,- liggen.";
            return $error;
        }
    }
    return "";
}
function checkBeschrijving($beschrijving) {
    if (strlen($beschrijving) < 3 || $beschrijving == "") {
        return false;
    }
    return true;
}
function checkTitel($naam) {
    if (strlen($naam) < 3) {
        return false;
    }
    return true;
}
function getUploadedFiles()
{
    $validfiles = array();
    var_dump($_FILES);
    foreach ($_FILES as $key => $value) {
        if ($value['size'] > 2000000)
            continue;
        if ($value['name'] != "" && ($value['type'] == "image/png" || $value['type'] == "image/jpg" || $value['type'] == "image/jpeg")) {
            array_push($validfiles, $value);
        }
    }
    return $validfiles;
}


function getHigh1()
{
    if (isset($_POST['category1'])) {
        if (isset($_POST['category1sub1'])) {
            if (isset($_POST['category1sub2'])) {
                if (isset($_POST['category1sub3'])) {
                    if (isset($_POST['category1sub4'])) {
                        return  $_POST['category1sub4'];
                    }
                    return $_POST['category1sub3'];
                }
                return $_POST['category1sub2'];
            }
            return $_POST['category1sub1'];
        }
        return $_POST['category1'];
    }
    return null;
}

function getHigh2()
{
    if (isset($_POST['category2'])) {
        if (isset($_POST['category2sub1'])) {
            if (isset($_POST['category2sub2'])) {
                if (isset($_POST['category2sub3'])) {
                    if (isset($_POST['category2sub4'])) {
                        return  $_POST['category2sub4'];
                    }
                    return $_POST['category2sub3'];
                }
                return $_POST['category2sub2'];
            }
            return $_POST['category2sub1'];
        }
        return $_POST['category2'];
    }
    return null;
}