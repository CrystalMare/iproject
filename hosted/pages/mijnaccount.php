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

    $buffer['']

}

function post() {
    global $buffer;

}

$user = $_SESSION['username'];

function nogTeGevenFeedbackOpGekochteArtikelen(){
    global $DB;
    SELECT Voorwerp.voorwerpnummer
    FROM Voorwerp
    WHERE Voorwerp.koper='satan'
    AND NOT EXISTS (SELECT Voorwerp.voorwerpnummer
                      FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
                      WHERE Voorwerp.koper='satan' AND Feedback.gebruikersoort='verkoper');
    $params = array($_SESSION['username']);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

function persoonsGegevens($user) {
    global $DB;
    $tsql = "SELECT gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, postcode, plaatsnaam,
    land, geboortedag, mailbox FROM Gebruiker WHERE gebruikersnaam = ?;";
    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}