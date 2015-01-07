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
    global $buffer, $DB;

    $buffer['status'] = "";



}

function get() {
    global $buffer;
    if (!isset($_SESSION['username']) || $_SESSION['username'] == "")
    {
        header("Location: index.php?page=inloggen");
        exit();
    }
    $gegevens = getGegevens($_SESSION['username']);
    //var_dump($gegevens);
    $buffer['voornaam'] = $gegevens['voornaam'];
    $buffer['achternaam'] = $gegevens['achternaam'];
    $buffer['adresregel1'] = $gegevens['adresregel1'];
    $buffer['adresregel2'] = $gegevens['adresregel2'];
    $buffer['postcode'] = $gegevens['postcode'];
    $buffer['plaatsnaam'] = $gegevens['plaatsnaam'];
    $buffer['telefoon1'] = $gegevens['telefoon1'];
    $buffer['telefoon2'] = $gegevens['telefoon2'];
    $buffer['dag'] = $gegevens['geboortedag']->format("d");
    $buffer['maand'] = $gegevens['geboortedag']->format("m");
    $buffer['jaar'] = $gegevens['geboortedag']->format("Y");



}
function changePassword($new, $user)
{
    global $DB;
    $tsql = "SELECT salt FROM Gebruiker WHERE gebruikersnaam = ?;";
    $stmt = sqlsrv_query($DB, $tsql, array($user));
    $salt = sqlsrv_fetch_array($stmt)['salt'];
    $hash = hash('sha256', $new . $salt);
    $tsql = "UPDATE Gebruiker SET wachtwoord = ? WHERE gebruikersnaam =?;";
    $stmt = sqlsrv_query($DB, $tsql, array($hash, $user));
    return sqlsrv_errors() == NULL;
}


function post()
{
    global $buffer;

    $array = getGegevens($_SESSION['username']);
    print_r("WACHTWOORD" . $array['salt'] . "\n");

    if($_POST['wachtwoord'] != "" && $_POST['herhaalWachtwoord'] != "" && $_POST['wachtwoord'] == $_POST['herhaalWachtwoord'] && strlen($_POST['wachtwoord']) > 6)
    {
        $newPassword = $_POST['herhaalWachtwoord'];
        $value = changePassword($newPassword, $_SESSION['username']);
        $buffer['status'] = $value ? "Je wachtwoord is aangepast homo" : " er is iets misgegaan stop met internetten!";

    }
    else
    {
        //maak dit mooier!!!
        $buffer['status'] = "CONTROLEER INPUT";
    }

    if($_POST['voornaam'] != $array['voornaam'] && $_POST['voornaam'] != "")
    {
        $array['voornaam'] = $_POST['voornaam'];
    }
    if($_POST['achternaam'] != $array['achternaam'] && $_POST['achternaam'] != "")
    {
        $array['achternaam'] = $_POST['achternaam'];
    }
    if($_POST['adresregel1'] != $array['adresregel1'] && $_POST['adresregel1'] != "")
    {
        $array['adresregel1'] = $_POST['adresregel1'];
    }
    if($_POST['adresregel2'] != $array['adresregel2'] && $_POST['adresregel2'] != "")
    {
        $array['adresregel2'] = $_POST['adresregel2'];
    }
    if($_POST['postcode'] != $array['postcode'] && $_POST['postcode'] != "")
    {
        $array['postcode'] = $_POST['postcode'];
    }
    if($_POST['plaatsnaam'] != $array['plaatsnaam'] && $_POST['plaatsnaam'] != "")
    {
        $array['plaatsnaam'] = $_POST['plaatsnaam'];
    }
    if($_POST['telefoon1'] != $array['telefoon1'] && $_POST['telefoon1'] != "")
    {
        $array['telefoon1'] = $_POST['telefoon1'];
    }
    if($_POST['telefoon2'] != $array['telefoon2'] && $_POST['telefoon2'] != "")
    {
        $array['telefoon2'] = $_POST['telefoon2'];
    }
    $date = "";
    if($_POST['jaar'] != "" && $_POST['maand'] != "" && $_POST['dag'] != "") {
        $date = DateTime::createFromFormat('Y-m-d', $_POST['jaar'] . '-' . $_POST['maand'] . '-' . $_POST['dag']);
        $date->format('Y-m-d');
    }
    if($date != $array['geboortedag']->format('Y-m-d'))
    {
        $array['geboortedag'] = $date;
    }

    var_dump($array);

    get();
    setGegevens($array);





}

function setGegevens($array)
{
    global $DB;

    $OLD = getGegevens($array['gebruikersnaam']);

    $tsql = "UPDATE gebruiker SET voornaam = ?, achternaam = ?, adresregel1 = ?, adresregel2 = ?, postcode = ?, plaatsnaam = ?, geboortedag = ?
             WHERE gebruikersnaam = ?";
    $tsql2 = "UPDATE gebruikerstelefoon SET telefoonnummer = ? WHERE telefoonnummer = ? AND gebruikersnaam = ?";
    if($array['telefoon2'] != "")
    {
        $params = array($array['telefoon2'], $OLD['telefoon2'], $OLD['gebruikersnaam']);
        sqlsrv_query($DB, $tsql2, $params);
    }
    if ($array['telefoon1'] != "")
    {
        $params = array($array['telefoon1'], $OLD['telefoon1'], $OLD['gebruikersnaam']);
        sqlsrv_query($DB, $tsql2, $params);
    }
    $params = array($array['voornaam'], $array['achternaam'], $array['adresregel1'], $array['adresregel2'], $array['postcode'], $array['plaatsnaam'],
                    $array['geboortedag'], $OLD['gebruikersnaam']);
    sqlsrv_query($DB, $tsql, $params);
}

function getGegevens($user)
{
    global $DB;

    $tsql = "SELECT voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam, geboortedag, gebruikersnaam, salt
from gebruiker
WHERE gebruikersnaam = ?";

    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $userinfo =  sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $tsql = "SELECT telefoonnummer FROM Gebruikerstelefoon WHERE gebruikersnaam = ?;";
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $userinfo['telefoon2'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['telefoonnummer'];
    $userinfo['telefoon1'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['telefoonnummer'];

    return $userinfo;
}

//function getItemInfo($auction) {
//    global $DB;
//    $tsql = "SELECT titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, verzendkosten
//            verzendinstructies, verkoper, looptijdeindmoment, gesloten FROM Voorwerp WHERE voorwerpnummer = ?;";
//    $params = array($auction);
//    $stmt = sqlsrv_query($DB, $tsql, $params);
//    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
//}
