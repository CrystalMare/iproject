<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 11-12-2014
 * Time: 13:27
 */

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
    global $buffer, $DB;

    $laatsteBod = "SELECT max(bodbedrag) FROM Bod WHERE voorwerpnummer = ?";

    $artikelGegevens = "SELECT titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, verzendinstructies, verkoper, looptijdeindmoment, gesloten FROM Voorwerp " .
        "WHERE voorwerpnummer = ?";

    $params = array(1);

    $stmt = sqlsrv_query($DB,$artikelGegevens, $params);
    $stmtLaatsteBod = sqlsrv_query($DB,$laatsteBod, $params);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $rowLaatsteBod = sqlsrv_fetch_array($stmtLaatsteBod, SQLSRV_FETCH_ASSOC);

    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }

    if(!sqlsrv_has_rows($stmt)) {
        $buffer['error'] = "Artikel bestaat niet.";
        return;
    }

    if($stmtLaatsteBod){
        if(!sqlsrv_has_rows($stmtLaatsteBod)){
            $buffer['bedrag'] = $row['startprijs'];
        }
        else{
            $buffer['bedrag'] = $rowLaatsteBod['bodbedrag'];
        }
    }
    else{
        $buffer['bedrag'] = $row['startprijs'];
    }





    $buffer['titel'] = $row['titel'];
    $buffer['beschrijving'] = $row['beschrijving'];
    $buffer['betalingswijze'] = $row['betalingswijze'];
    $buffer['plaatsnaam'] = $row['plaatsnaam'];
    $buffer['land'] = $row['land'];
    $buffer['verzendinstructies'] = $row['verzendinstructies'];
    $buffer['looptijdeindmoment'] = $row['looptijdeindmoment']->format('Y-m-d H:i:s');
    $buffer['gesloten'] = $row['gesloten'];

    $buffer['laatstebod'] = $row['gesloten'];
}

function post() {
    global $buffer;

}



