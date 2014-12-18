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
    $buffer['history'] = "";

}

function get()
{
    global $buffer, $DB;
    $auction = 3;

    $bidhistory = getBidHistory($auction);
    $iteminfo = getItemInfo($auction);

    $laatsteBod = isset($bidhistory[0]) ? $bidhistory[0] : $iteminfo['startprijs'];

    $params = array(1);

    if (isset($bidhistory[0])) {
        $buffer['bedrag'] = $bidhistory[0]['bodbedrag'];
    } else {
        $buffer['bedrag'] = $iteminfo['startprijs'];
    }

    $buffer['titel'] = $iteminfo['titel'];
    $buffer['startprijs'] = $iteminfo['startprijs'];
    $buffer['beschrijving'] = $iteminfo['beschrijving'];
    $buffer['betalingswijze'] = $iteminfo['betalingswijze'];
    $buffer['plaatsnaam'] = $iteminfo['plaatsnaam'];
    $buffer['land'] = $iteminfo['land'];
    $buffer['verkoper'] = $iteminfo['verkoper'];
    $buffer['verzendinstructies'] = $iteminfo['verzendinstructies'];
    $buffer['eindmoment'] = $iteminfo['looptijdeindmoment']->format('Y-m-d H:i:s');
    $buffer['gesloten'] = $iteminfo['gesloten'];
    $buffer['laatstebod'] = $iteminfo['gesloten'];

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

    foreach($bidhistory as $key => $value) {
        $user = $value['gebruikersnaam'];
        $ammount = $value['bodbedrag'];
        $datetime = $value['datumtijd']->format('Y-m-d H:i:s');
        if ($key == 0 ) {
            $buffer['history'] .= "<b>$user | $datetime | &#8364;$ammount</b><br />";
        } else {
            $buffer['history'] .= "$user | $datetime | &#8364;$ammount<br />";
        }
    }

}

function post() {
    global $buffer, $DB;



}

function bodBevestigen($bod,$gebruiker,$veiling){
    global $DB;
    $sql = "INSERT INTO Bod (voorwerpnummer, gebruikersnaam, bodbedrag )
            VALUES (?, ?, ?)";
    $params = array ($veiling, $gebruiker, $bod);
    $stmt = sqlsrv_query($DB, $sql, $params);




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

function getItemInfo($auction) {
    global $DB;
    $tsql = "SELECT titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land,
            verzendinstructies, verkoper, looptijdeindmoment, gesloten FROM Voorwerp WHERE voorwerpnummer = ?;";
    $params = array($auction);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}
