<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 11-12-2014
 * Time: 10:01
 */


setDefaultHeaderBuffer();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getHeader();
        break;
    case 'POST':
        postHeader();
        break;
    default:
        getHeader();
}

function setDefaultHeaderBuffer() {
    global $buffer;
    $buffer['headercategorie'] = "";

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