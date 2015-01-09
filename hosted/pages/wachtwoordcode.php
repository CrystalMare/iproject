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

    $buffer['error'] = "";
    $buffer['status'] = "";


}

function get() {

    global $buffer, $DB;

    //todo: Check if key is valid
    //var_dump($_GET);
    $_SESSION['username'] = $_GET['username'];
    $_SESSION['key'] = $_GET['key'];
    //return;
    //var_dump($_SESSION);
    if (isset($_GET['error'])) {
        $buffer['error'] = $_GET['error'];
    }


    $tsql = "SELECT vraag.vraag, gebruiker.salt, gebruiker.antwoordtekst
    FROM Gebruiker JOIN vraag on gebruiker.vraag = vraag.vraagnummer
    WHERE gebruiker.gebruikersnaam = ?;";

    $params = array($_SESSION['username']);

    $stmt = sqlsrv_query($DB, $tsql, $params);
    $result = sqlsrv_fetch_array($stmt);
    //var_dump($result);

    $buffer['vraag'] = $result['vraag'];
    //var_dump($buffer['vraag']);
    //$buffer['vraag'] = 'gay';



}

function post() {
    global $buffer, $DB;

    //var_dump($_POST);

    $username = $_SESSION['username'];
    $oldhash = getOldHash($username);

    $error = "";
    var_dump(checkQuestion($_POST['antwoord'], $oldhash));
    if(isset($_POST['antwoord']) && $_POST['antwoord'] != "") {
        if (checkQuestion($_POST['antwoord'], $oldhash)) {
            $_SESSION['restore'] = $username;
            header("Location: index.php?page=wachtwoordWijzigen");
        }
        else {
            $error = "Antwoord komt niet overeen, probeer opnieuw.";

            header("Location: index.php?page=wachtwoordcode&key=" . $_SESSION['key'] . "&username=" . $username . "&error=" . $error);
        }
    }
    else {
        $error = "Geen geldige invoer, probeer opnieuw.";
        header("Location: index.php?page=wachtwoordcode&key=" . $_SESSION['key'] . "&username=" . $username . "&error=" . $error);
    }
}

function checkQuestion($antwoord, $oldHash)
{
    $hashNew =  hash("sha256", $antwoord);
    print_r($hashNew);
    print_r("-----------");
    print("HELLO WORLD! \n");
    print_r($oldHash['antwoordtekst']);


    if ($oldHash['antwoordtekst'] == $hashNew)
    {
        print_r("TRUETRUETRUE");
        return true;
    }

        return false;

}

function getOldHash($username)
{
    global $DB;
    $tsql = "SELECT antwoordtekst
            FROM gebruiker
            WHERE gebruikersnaam = ?;";
        $params = array($username);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $antwoord = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    print_r($antwoord);

    return $antwoord;
}
