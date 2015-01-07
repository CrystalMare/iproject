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
    $buffer['foutcreditcardnummer']="";
    $buffer['select'] = <<<"END"
            <option value="none" selected ></option>
            <option value="creditcard">Creditcard</option>
            <option value="post" >Post</option>
END;
}

function get() {
    global $buffer;
    if(!isset($_SESSION['username'])){
        header('Location: index.php');
    }
}

function post() {
    global $buffer;
    $buffer['foutcreditcardnummer']="";

    if($_POST['identificatiemethode'] == "creditcard"){
        invoerveldCreditcard();
        $buffer['select'] = <<<"END"
		<option value="creditcard" selected>Creditcard</option>
        <option value="post" >Post</option>
END;
    } else if($_POST['identificatiemethode'] == "post"){
        $buffer['select'] = <<<"END"
		<option value="creditcard" >Creditcard</option>
        <option value="post" selected>Post</option>
END;
    }

    if($_POST['submit'] == 'accepteer') {
        if ($_POST['identificatiemethode'] == 'creditcard') {
            if (checkLuhn($_POST['creditcard'])) {
                verkoperRegistratieCreditcard($_SESSION['username'], $_POST['creditcard']);
                header('Location: index.php');
            } else {
                $buffer['foutcreditcardnummer'] = "Het creditcardnummer is verkeerd.";
            }
        } else if ($_POST['identificatiemethode'] == 'post') {
            verkoperRegistratiebrief($_SESSION['username']);
            header('Location: index.php');
        }
    }
}

function verkoperRegistratieBrief($gebruiker){
    global $DB;
    $sql = "INSERT INTO Verkoperverificatie (gebruikersnaam)
            VALUES (?)";
    $params = array ($gebruiker);
    sqlsrv_query($DB, $sql, $params);
}

function verkoperRegistratieCreditcard($gebruiker, $creditcardnummer){
    global $DB;
    $sql = "INSERT INTO Verkoper (gebruikersnaam, controleoptie, creditcard)
            VALUES (?, 'creditcard', ?)";
    $params = array ($gebruiker, $creditcardnummer);
    sqlsrv_query($DB, $sql, $params);
}

function invoerveldCreditcard(){
    global $buffer;
    $buffer['creditcardnummer'] .= <<<"END"
      <label for="creditcard nummer">Voer creditcard nummer in</label>
      <input type="text" class="form-control" name="creditcard" id="creditcard" placeholder="Creditcard nummer" autocomplete="off" style="cursor:auto;">
END;
}
