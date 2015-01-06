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
    $buffer['creditcardnummer']="";
    $buffer['selected']="";
    $buffer['selected1'] ="";
    $buffer['selected2'] ="";
}

function get() {
    global $buffer;
}

function post() {
    global $buffer;
    var_dump($_POST);

    if($_POST['identificatiemethode'] == "Creditcard"){
        $buffer['selected1'] = "creditcard";
    } else if($_POST['identificatiemethode'] == "Post"){
        $buffer['selected2'] = "Post";
    }

    if(!isset($_POST['identificatiemethode']))
        return;

    if ($_POST['identificatiemethode'] == 'Creditcard') {
        if(checkLuhn($_POST['creditcard'])){
            verkoperRegistratieCreditcard($_SESSION['username'], $_POST['creditcard']);
        }
    } else if ($_POST['identificatiemethode'] == 'Post') {
        verkoperRegistratiebrief($_SESSION['username']);
    }
}

function verkoperRegistratieBrief($gebruiker){
    global $DB;
    $sql = "INSERT INTO Verkoperverificatie (gebruikersnaam)
            VALUES (?)";
    $params = array ($gebruiker);
    sqlsrv_query($DB, $sql, $params);
    var_dump(sqlsrv_errors());
}

function verkoperRegistratieCreditcard($gebruiker, $creditcardnummer){
    global $DB;
    $sql = "INSERT INTO Verkoper (gebruikersnaam, controleoptie, creditcard)
            VALUES (?, 'creditcard', ?)";
    $params = array ($gebruiker, $creditcardnummer);
    sqlsrv_query($DB, $sql, $params);
    var_dump(sqlsrv_errors());
}

function invoerveldCreditcard(){
    global $buffer;
    $buffer['creditcardnummer'] .= <<<"END"
      <label for="creditcard nummer">Voer creditcard nummer in</label>
      <input type="text" class="form-control" id="creditcard" placeholder="Creditcard nummer" autocomplete="off" style="cursor:auto;">
END;
}
