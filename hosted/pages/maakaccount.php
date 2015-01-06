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
    header("Location: ?page=registreren");
    exit();
}

function post() {
    global $buffer;
    $state = verifyRegistration();
    if ($state == 'ok') {
        $state = commitRegistration();
    } else {

        $key = $_SESSION['register']['key'];
        header("Location: ?page=registreeraccount&key=$key&err=$state");
        exit();
    }


    if ($state != 'ok') {
        $key = $_SESSION['register']['key'];
        header("Location: ?page=registreeraccount&key=$key&err=$state");
        exit();
    }

}
function verifyRegistration() {
    global $DB;
    if (!isset($_POST['username'])) return "field_missing";
    $user = $_POST['username'];
    //Check username
    $sql = "SELECT gebruikersnaam FROM Gebruiker WHERE gebruikersnaam = '?';";
    $stmt = sqlsrv_query($DB, $sql, array($user));
    if (!$stmt) return "user_exsits";
    if (sqlsrv_has_rows($stmt)) return "user_exists";

    //Check pw
    if (strlen(($_POST['password'])) < 6) {
        return "pw_short";
    } elseif ($_POST['password'] != $_POST['repeatpassword']) {
        return "pw_notsame";
    }

    if ($_SESSION['register']['key'] != $_GET['key']) return "key_error";
    return 'ok';
}

function commitRegistration() {
    global $DB;
    $sql =    "INSERT INTO Gebruiker "
        . "(gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, "
        . "postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, salt) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $salt = randomSalt();
    $wachtwoord = hash('sha256', $_POST['password'] . $salt);
    $antwoord = hash('sha256', $_POST['questionAnswer']);
    $geboortedag = $_POST['year'] . $_POST['month'] . $_POST['day'];
    $params = array(
        $_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['adres'], $_POST['adres2'],
        str_replace(" ", "", $_POST['zipcode']), $_POST['town'], $_POST['country'], $geboortedag,
        $_SESSION['register']['email'], $wachtwoord, $_POST['vraag'], $antwoord, $salt
    );
    var_dump($params);
    $stmt = sqlsrv_query($DB, $sql, $params);
    if (!sqlsrv_errors()) {
        return "ok";
    } else {
        return "check";
    }
}

function randomSalt() {
    $rawsalt = uniqid();
    $salt = substr($rawsalt, 0, 8);
    return $salt;
}