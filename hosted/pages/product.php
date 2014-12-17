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

function get()
{
    global $buffer, $DB;

    $laatsteBod = "SELECT max(bodbedrag) AS bodbedrag FROM Bod WHERE voorwerpnummer = ?";

    $artikelGegevens = "SELECT titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land, verzendinstructies, verkoper, looptijdeindmoment, gesloten FROM Voorwerp " .
        "WHERE voorwerpnummer = ?";

    $params = array(1);

    $stmt = sqlsrv_query($DB, $artikelGegevens, $params);
    $stmtLaatsteBod = sqlsrv_query($DB, $laatsteBod, $params);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $rowLaatsteBod = sqlsrv_fetch_array($stmtLaatsteBod, SQLSRV_FETCH_ASSOC);

    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }

    if (!sqlsrv_has_rows($stmt)) {
        $buffer['error'] = "Artikel bestaat niet.";
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
    $buffer['beschrijving'] = $row['beschrijving'];
    $buffer['betalingswijze'] = $row['betalingswijze'];
    $buffer['plaatsnaam'] = $row['plaatsnaam'];
    $buffer['land'] = $row['land'];
    $buffer['verzendinstructies'] = $row['verzendinstructies'];
    $buffer['looptijdeindmoment'] = $row['looptijdeindmoment']->format('Y-m-d H:i:s');
    $buffer['gesloten'] = $row['gesloten'];
    $buffer['laatstebod'] = $row['gesloten'];

$auction = 1;
    for ($i = 0; $i < ImageProvider::getImagesForAuction(1)->getImageCount(); $i++) {

    }
    var_dump(ImageProvider::getImagesForAuction($auction)->getImageCount());

    if(ImageProvider::getImagesForAuction($auction)->getImageCount() > 0)   {
        $buffer['pic'] = <<<"END"
        <div class="col-md-12 kleine-thumbnail col-xs-12">
                    <a href="#" data-toggle="modal" data-target="#basicModal0">
                        <img src="inc/image.php?auction=$auction&id=0" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal0" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=0" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;
        }

    else If(ImageProvider::getImagesForAuction($auction)->getImageCount() > 1)   {
        $buffer['pic'] .= <<<"END"

    <div class="col-md-4 kleine-thumbnail col-xs-4">
                    <a href="#" data-toggle="modal" data-target="#basicModal1">
                        <img src="inc/image.php?auction=$auction &id=1" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal1" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=1" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;
    }
    else If(ImageProvider::getImagesForAuction($auction)->getImageCount() > 2)   {
        $buffer['pic'] .= <<<"END"
                    <div class="col-md-4 kleine-thumbnail col-xs-4">
                    <a href="#" data-toggle="modal" data-target="#basicModal2">
                        <img src="inc/image.php?auction=$auction&id=2" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal2" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=2" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;
    }
    else If(ImageProvider::getImagesForAuction($auction)->getImageCount() > 3)   {
        $buffer['pic'] .= <<<"END"

                    <div class="col-md-4 kleine-thumbnail col-xs-4">
                    <a href="#" data-toggle="modal" data-target="#basicModal3">
                        <img src="inc/image.php?auction=$auction&id=3" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal3" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=3" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;
    }
    if(!isset($buffer['pic'])){
        $buffer['pic'] = <<<"END"

                    <div class="col-md-12 kleine-thumbnail col-xs-12">
                    <a href="#" data-toggle="modal" data-target="#basicModal3">
                        <img src="img/logo_header.png" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal3" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">test titel</h5>
                            </div>
                            <div class="modal-body">
                                <img src="img/logo_header.png   " alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;
    }


    var_dump($buffer);

}

function post() {
    global $buffer;

}



