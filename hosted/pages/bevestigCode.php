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
    $buffer['meldingbevestigingscode']="";
}

function get() {
    global $buffer;
}

function post() {
    global $buffer;

    $buffer['meldingbevestigingscode']="";
    if(empty($_POST['bevestigingscodeverkoper']) or empty($_POST['bank']) or empty($_POST['rekeningnummer'])){
        $buffer['meldingbevestigingscode']="Voer alle velden in.";
    } else if($_POST['submit'] == 'bevestig'){
        $hash = hash('sha256', $_SESSION['username']."post");
        if($hash == $_POST['bevestigingscodeverkoper']){
            registrerenVerkoper($_SESSION['username'], $_POST['bank'], $_POST['rekeningnummer']);
            verkoperverificatieVerwijder($_SESSION['username']);
            header('Location: index.php');
        } else {
            $buffer['meldingbevestigingscode']="Bevestigingsode is niet correct.";
        }
    }
}

function registrerenVerkoper($gebruiker, $bank, $rekeningnummer){
    global $DB;
    $sql = "INSERT INTO Verkoper (gebruikersnaam, bank, rekeningnummer, controleoptie)
            VALUES (?, ?, ?, 'post')";
    $params = array ($gebruiker, $bank, $rekeningnummer);
    sqlsrv_query($DB, $sql, $params);
}

function verkoperverificatieVerwijder($gebruiker){
    global $DB;
    $sql = "DELETE FROM Verkoperverificatie WHERE gebruikersnaam = ?";
    $params = array ($gebruiker);
    sqlsrv_query($DB, $sql, $params);
}