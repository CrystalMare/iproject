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
    $buffer['pic'] = "";

}

function get()
{
    global $buffer, $DB;

    $laatsteBod = "SELECT max(bodbedrag) AS bodbedrag FROM Bod WHERE voorwerpnummer = ?";

    $bodGeschiedenis ="SELECT bodbedrag, gebruikersnaam, datumtijd FROM Bod WHERE voorwerpnummer = ? ORDER BY bodbedrag DESC;";

    $artikelGegevens = "SELECT titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land,
            verzendinstructies, verkoper, looptijdeindmoment, gesloten FROM Voorwerp WHERE voorwerpnummer = ?";

    $params = array(1);

    $stmt = sqlsrv_query($DB, $artikelGegevens, $params);
    $stmtLaatsteBod = sqlsrv_query($DB, $laatsteBod, $params);
    $stmtBodGeschiedenis = sqlsrv_query($DB, $bodGeschiedenis, $params);

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $rowLaatsteBod = sqlsrv_fetch_array($stmtLaatsteBod, SQLSRV_FETCH_ASSOC);
    $rowBodGeschiedenis = sqlsrv_fetch_array($stmtBodGeschiedenis, SQLSRV_FETCH_ASSOC);


    foreach(getBidHistory(1) as $value) {
        var_dump($value);
    }



    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }

    if (!sqlsrv_has_rows($stmt)) {
        $buffer['error'] = "Artikel bestaat niet.";
        return;
    }

    if (!sqlsrv_has_rows($stmtBodGeschiedenis)) {
        $buffer['error'] = "Er is nog niet eerder geboden.";
        return;
    }

    if ($stmtLaatsteBod) {
        if (sqlsrv_has_rows($stmtLaatsteBod)) {
            $buffer['bedrag'] = $rowLaatsteBod['bodbedrag'];
        } else {
            $buffer['bedrag'] = $row['startprijs'];
        }
    } else {
        $buffer['bedrag'] = $row['startprijs'];
    }

    $buffer['titel'] = $row['titel'];
    $buffer['startprijs'] = $row['startprijs'];
    $buffer['beschrijving'] = $row['beschrijving'];
    $buffer['betalingswijze'] = $row['betalingswijze'];
    $buffer['plaatsnaam'] = $row['plaatsnaam'];
    $buffer['land'] = $row['land'];
    $buffer['verkoper'] = $row['verkoper'];
    $buffer['verzendinstructies'] = $row['verzendinstructies'];
    $buffer['looptijdeindmoment'] = $row['looptijdeindmoment']->format('Y-m-d H:i:s');
    $buffer['gesloten'] = $row['gesloten'];
    $buffer['laatstebod'] = $row['gesloten'];

    $buffer['geschiedenis'] = $rowBodGeschiedenis['bodbedrag'];
    $buffer['bieder'] = $rowBodGeschiedenis['gebruikersnaam'];
$auction = 3;

    for($count = 0; $count < ImageProvider::getImagesForAuction($auction)->getImageCount(); $count++) {
        $col = $count==0 ? 12 : 4;
        $buffer['pic'] .= <<<"END"
    <div class="col-md-$col kleine-thumbnail col-xs-$col">
                    <a href="#" data-toggle="modal" data-target="#basicModal$count">
                        <img src="inc/image.php?auction=$auction&id=$count" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal$count" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=$count" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;

    }

}

function post() {
    global $buffer;

}


function getBidHistory($auction) {
    global $DB;
    $tsql = "SELECT bodbedrag, gebruikersnaam, datumtijd FROM Bod WHERE voorwerpnummer = 1 ORDER BY bodbedrag DESC;";
    $params = array($auction);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $history = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($history, $row);
    }
    return $history;
}
