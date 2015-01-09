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
    $buffer['veiling'] = "";

}

function get() {
    global $buffer;


        foreach (veilingen($_SESSION['username']) as $veiling) {

            $image = ImageProvider::getImagesForAuction($veiling['voorwerpnummer'])->getImage(0);

            $buffer['veiling'] .= <<<"END"
                <div class ="col-md-12 col-xs-12">
                    <div class ="col-md-2 col-xs-2">
                        <img src="$image" alt="geen foto" class="img-thumbnail" >
                    </div>
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        <h5>$veiling[titel]</h5>
                        $veiling[beschrijving];
                    </div>
                    <div class ="col-md-3 col-xs-3">
                        <a href="?page=product&veiling=$veiling[voorwerpnummer]" class="btn btn-warning bodplaatsen" id="$veiling[voorwerpnummer]" >Ga naar veiling</a>
                    </div>
                </div>
END;


        }

}

function post() {
    global $buffer;

}

function veilingen($user)
{
    global $DB;

    $tsql = "SELECT voorwerpnummer, titel, beschrijving  FROM Voorwerp
    WHERE verkoper = ?";
    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $veilinginfo = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($veilinginfo, $row);
    }
    return $veilinginfo;
}