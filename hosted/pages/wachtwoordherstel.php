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

    $buffer['emailerror'] = "";
}

function get() {
    global $buffer;


}
function post()
{
    global $buffer;
    //var_dump($_POST['email']);
    if (!isset($_POST['email']) || $_POST['email'] == "") {
        $buffer['emailerror'] = "Voer een geldig emailadres in";
        return;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $buffer['emailerror'] = "Dit is geen geldig emailadres";
        return;
    }
    //return;

    //EMAIL VALIDEREN!!!!
    require(inc . 'mail.php');

    if (isset($_POST['email']) && $_POST['email'] != "") {
        $gegevens = getAccountGegevens($_POST['email']);

        if (sendMail($_POST['email'], "Wachtwoord vergeten - Eenmaal Andermaal", getBody($gegevens['gebruikersnaam'], $gegevens['salt']))) {
            $buffer['emailerror'] = "Er is een email verstuurd naar " . $_POST['email'];
            header("Location: index.php?page=codeVerstuurd");
        } else {
            $buffer['emailerror'] = "Er is iets mis gegaan. Probeer het nog een keer.";
        }
    }
    return;
}
function getAccountGegevens($email)
{
    global $DB;
    $tsql = "SELECT gebruikersnaam, salt
             FROM gebruiker
             WHERE mailbox = ?;";

        $params = array($email);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $accountGegevens = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    return $accountGegevens;
}


function getBody($username, $salt) {
    global $CONFIG;
    $hash = hash("sha256", $username . $salt .  "herstel");
    $link = "http://" . $CONFIG['site']['host'] . "?page=wachtwoordcode&key=" . $hash . "&username=" . $username;

    return "Geachte mevrouw, mijnheer, <br />" .
        "Hierbij willen we u graag de link voor het herstellen van uw wachtwoord toesturen, <br />" .
        "<a href='$link'>$link</a><br />" .
        "Vr. gr., Eenmaal Andermaal";
}


