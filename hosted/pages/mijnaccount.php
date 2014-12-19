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
//    $tsql = "SELECT Voorwerp.voorwerpnummer, Bestand.filenaam, Voorwerp.titel, Voorwerp.beschrijving
//        FROM Feedback INNER JOIN (Voorwerp INNER JOIN Bestand ON Voorwerp.voorwerpnummer = Bestand.voorwerpnummer)
//        ON Feedback.voorwerpnummer = Voorwerp.voorwerpnummer
//        WHERE (((Feedback.gebruikersoort)="koper"))";
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