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
    $buffer['artikel']="";

}

function get() {
    global $buffer;
    $user = 'satan';


    $getGegevens = getGegevens($user);

        $buffer['voornaam'] = $getGegevens['voornaam'];
        $buffer['achternaam'] = $getGegevens['achternaam'];
        $buffer['gebruikersnaam'] = $getGegevens['gebruikersnaam'];
        $buffer['emailadres'] = $getGegevens['mailbox'];
        $buffer['adres1'] = $getGegevens['adresregel1'];
        $buffer['adres2'] = $getGegevens['adresregel2'];
        $buffer['postcode'] = $getGegevens['postcode'];
        $buffer['plaats'] = $getGegevens['plaatsnaam'];
        $buffer['land'] = $getGegevens['land'];
        $buffer['telefoon1'] = $getGegevens['telefoon1'];
        $buffer['telefoon2'] = $getGegevens['telefoon2'];
        $buffer['geboortedatum'] = $getGegevens['geboortedag']->format('Y-m-d');
        $buffer['verkoper'] = $getGegevens['verkoper'];

    foreach (nogTeGevenFeedbackOpGekochteArtikelen($user) as $veiling) {

        var_dump(artikelGegevens($veiling));

        $buffer['artikel'] .= <<<"END"
                <div class ="col-md-12 col-xs-12">
                    <div class ="col-md-2 col-xs-2">
                        <img src="inc/image.php?auction=$veiling[voorwerpnummer]&id=0" alt="geen foto" class="img-thumbnail" >
                    </div>
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        $test
                    </div>
                    <div class ="col-md-3 col-xs-3">
                        <a href="#" class="btn btn-warning bodplaatsen" data-toggle="modal" data-target="#feedback-modal">Geef feedback</a>
                    </div>
                </div>
END;

    }
}

function post() {
    global $buffer;


}

function nogTeGevenFeedbackOpGekochteArtikelen($user){
    global $DB;
    $tsql = "SELECT Voorwerp.voorwerpnummer
    FROM Voorwerp
    WHERE Voorwerp.koper=?
    AND NOT EXISTS (SELECT Voorwerp.voorwerpnummer
                      FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
                      WHERE Voorwerp.koper=? AND Feedback.gebruikersoort='verkoper')";
    $params = array($user, $user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $feedback = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($feedback, $row);
    }
    return $feedback;
}





function getGegevens($user)
{
    global $DB;

    $tsql = "SELECT voornaam, achternaam, gebruikersnaam, mailbox, adresregel1, adresregel2, postcode, plaatsnaam,
    land, geboortedag, verkoper FROM gebruiker
WHERE gebruikersnaam = ?";

    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $userinfo =  sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $tsql = "SELECT telefoonnummer FROM Gebruikerstelefoon WHERE gebruikersnaam = ?;";
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $userinfo['telefoon1'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['telefoonnummer'];
    $userinfo['telefoon2'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['telefoonnummer'];

    return $userinfo;
}

function artikelGegevens($voorwerpnummer){
    global $DB;
    $tsql = "SELECT titel, beschrijving FROM Voorwerp WHERE Voorwerpnummer = ?;";
    $params = array($voorwerpnummer);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}