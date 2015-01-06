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
    $buffer['artikel2']="";
    $buffer['verkoperAccountAanvragenKnop'] = "";
    $buffer['bevestigingsCodeKnop'] ="";
    $buffer['verkoperAccountAanvragenKnop'] ="";
}

function get() {
    global $buffer;


    $getGegevens = getGegevens($_SESSION['username']);


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

    foreach (nogTeGevenFeedbackOpGekochteArtikelen($_SESSION['username']) as $veiling) {

        $Gegevens = artikelGegevens($veiling[voorwerpnummer]);


        $image = ImageProvider::getImagesForAuction($veiling['voorwerpnummer'])->getImage(0);
        $buffer['artikel'] .= <<<"END"
                <div class ="col-md-12 col-xs-12">
                    <div class ="col-md-2 col-xs-2">
                        <img src="$image" alt="geen foto" class="img-thumbnail" >

                    </div>
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        <h5>$Gegevens[titel]</h5>
                        $Gegevens[beschrijving]
                    </div>
                    <div class ="col-md-3 col-xs-3">
                        <a href="#" class="btn btn-warning bodplaatsen" data-toggle="modal" data-target="#feedback-modal">Geef feedback</a>
                    </div>
                </div>
END;

    }

    if(verkoopAccountAanvraagKnop($_SESSION['username'])){
        $buffer['verkoperAccountAanvragenKnop'] .= <<<"END"
        <a href="?page=verkoopaccount" class="btn btn-primary bodplaatsen ">Verkoopaccount <br>aanmaken</a>
END;
    }

    if(bevestigingsCodeKnop($_SESSION['username']) == $_SESSION['username']){
        $buffer['bevestigingsCodeKnop'] .= <<<"END"
        <a href="?page=bevestigCode" class="btn btn-primary bodplaatsen ">Invoer <br>bevestigingscode</a>
END;
    }







    foreach (nogTeGevenFeedbackAanKoper($_SESSION['username']) as $veiling) {


        $Gegevens = artikelGegevens($veiling['voorwerpnummer']);

        $image = ImageProvider::getImagesForAuction($veiling['voorwerpnummer'])->getImage(0);
        $buffer['artikel2'] .= <<<"END"
                <div class ="col-md-12 col-xs-12">
                    <div class ="col-md-2 col-xs-2">
                        <img src="$image" alt="geen foto" class="img-thumbnail" >
                    </div>
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        <h5>$Gegevens[titel]</h5>
                        $Gegevens[beschrijving];
                    </div>
                    <div class ="col-md-3 col-xs-3">
                        <a href="#" class="btn btn-warning bodplaatsen" id="$veiling[voorwerpnummer]" data-id=$veiling[voorwerpnummer] data-toggle="modal" data-target="#feedback-modal">Geef feedback</a>
                    </div>
                </div>
END;


    }


}

function post() {
    global $buffer;
    setFeedback();
}

function setFeedback(){
    global $DB;
    $tsql = "INSERT INTO Feedback (commentaar, feedbacktype, gebruikersoort, voorwerpnummer)
              VALUES ('?','?','?','?')";
    $params = array();
    sqlsrv_query($DB,$tsql,$params);
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

function nogTeGevenFeedbackAanKoper($user){
    global $DB;
    $tsql = "SELECT Voorwerp.voorwerpnummer
    FROM Voorwerp
    WHERE Voorwerp.verkoper=?
    AND NOT EXISTS (SELECT Voorwerp.voorwerpnummer
                      FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
                      WHERE Voorwerp.verkoper=? AND Feedback.gebruikersoort='koper')";
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

function bevestigingsCodeKnop($user){
    global $DB;
    $tsql = "SELECT gebruikersnaam FROM Verkoperverificatie WHERE gebruikersnaam = ?;";
    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

function verkoopAccountAanvraagKnop($user){
    global $DB;
    $tsql = "SELECT gebruikersnaam
    FROM Gebruiker
    WHERE Gebruikersnaam=? and verkoper = 1
    union all
    SELECT Gebruikersnaam
        FROM Verkoperverificatie
        WHERE gebruikersnaam=?";
    $params = array($user, $user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return !sqlsrv_has_rows($stmt);
}