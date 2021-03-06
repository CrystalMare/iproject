<?php

setDefaultHeaderBuffer();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getHeader();
        getHeaderCategories();
        break;
    case 'POST':
        postHeader();
        getHeaderCategories();
        break;
    default:
        getHeader();
}

function setDefaultHeaderBuffer() {
    global $buffer;
    $buffer['headercategorie'] = "";
    $buffer['categorieenbalk'] = "";
}

function getHeader() {
    global $buffer;
    global $DB;
    if(!isset($_SESSION['username'])|| $_SESSION['username'] == null) {
        $buffer['menu1'] = "registreren";
        $buffer['menu11'] = "Registreren";
        $buffer['menu2'] = "inloggen";
        $buffer['menu22'] = "Inloggen";
    } else {
        $buffer['menu1'] = "mijnaccount";
        $buffer['menu11'] = "Mijn account";
        $buffer['menu2'] = "uitloggen";
        $buffer['menu22'] = "Uitloggen";
    }

//      <li><a href="#">Categorie #1</a></li>
//      <li><a href="#">Categorie #2</a></li>
    $sql = "SELECT rubrieknaam, rubrieknummer FROM Rubriek WHERE ouderrubriek IS NULL ORDER BY volgnummer ASC;";
    $stmt = sqlsrv_query($DB, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $title = $row['rubrieknaam'];
        $number = $row['rubrieknummer'];
        $buffer['headercategorie']  .= "<li class='hdclick' id='hd$number'><a>$title</a></li>";
    }

}

function postHeader() {
    global $buffer;


    if(!isset($_SESSION['username'])|| $_SESSION['username'] == null) {
        $buffer['menu1'] = "registreren";
        $buffer['menu11'] = "Registreren";
        $buffer['menu2'] = "inloggen";
        $buffer['menu22'] = "Inloggen";
    } else {
        $buffer['menu1'] = "mijnaccount";
        $buffer['menu11'] = "Mijn account";
        $buffer['menu2'] = "uitloggen";
        $buffer['menu22'] = "Uitloggen";
    }



}

function getHeaderCategories() {
    global $DB;
    global $buffer;
    $tsql = "SELECT TOP 5 rubrieknaam, volgnummer, rubrieknummer
            FROM Rubriek
            WHERE Ouderrubriek is null
            ORDER BY Volgnummer ASC, rubrieknaam ASC";
    $stmt = sqlsrv_query($DB, $tsql);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rubrieknaam = $row['rubrieknaam'];

        $idcount = 'c' . $row['rubrieknummer'];
        $template = <<<"END"
        <li id="$idcount" class="cat"><a href="#">$rubrieknaam</a></li>

END;
        $buffer['categorieenbalk'] .= $template;

    }
}