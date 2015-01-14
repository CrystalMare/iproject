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
    $buffer['hallo'] = "";
}

function get() {
    global $buffer, $DB;
}

function post() {
    global $buffer,$DB;

    // Controle of benodigde velden wel ingevuld zijn
    if(!isset($_POST['username'], $_POST['password'])) {
        $buffer['error'] = "Voer gebruikersnaam en wachtwoord in.";
        return;
    }

    // Gebruikersnaam en wachtwoord instellen
    $wachtwoordControle = "SELECT wachtwoord, salt, gebruikersnaam FROM Gebruiker " .
        "WHERE gebruikersnaam = ? AND verwijderd = 0;";

    $params = array($_POST['username']);

    $stmt = sqlsrv_query($DB,$wachtwoordControle, $params);
    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }

    if(!sqlsrv_has_rows($stmt)) {
        $buffer['error'] = "Voer gebruikersnaam en wachtwoord in.";
        return;
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $hash = hash('sha256', $_POST['password'] . $row['salt']);
    if($hash != $row['wachtwoord']){
        $buffer['error'] = "Voer gebruikersnaam en wachtwoord in.";
        return;
    }

    $_SESSION['username'] = $row['gebruikersnaam'];

    header("Location: index.php?page=index");


}










