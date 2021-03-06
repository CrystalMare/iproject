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
    $buffer['pic'] = "";
    $buffer['history'] = "";
    $buffer['error'] = 0;
    $buffer['verzendkosten'] = "geen";
    $buffer['beoordeling'] = "";
    $buffer['foutmelding'] = "";
    $buffer['categorie1'] = "";
    $buffer['categorie2'] = "";
    $buffer['minimalebedrag'] = "";
}

function get()
{
    global $buffer;

    $auction = $_GET['veiling'];
    $bidhistory = getBidHistory($auction);
    $iteminfo = getItemInfo($auction);

    if(isset($_GET['action'])&&$_GET['action']=="bied"){
        if ($_SESSION['username'] == null || $_SESSION['username'] == "") {
            header("Location: index.php?page=inloggen");
            exit;
        }
        if ($_SESSION['username'] == $iteminfo['verkoper'])
        {
            header("Location: index.php?page=product&veiling=$auction&err=nieteigen");
        } else {
            bodPlaatsen(getMinimumVerhoging(hoogsteBod($iteminfo, $bidhistory))+hoogsteBod($iteminfo, $bidhistory), $_SESSION['username'], $iteminfo['voorwerpnummer']);
        }
    }

    if(isset($_GET['err'])) {
        if ($_GET['err'] == 'nieteigen') {
            $buffer['foutmelding'] = "U kunt niet op uw eigen veiling bieden!";
        }
        if ($_GET['err'] == 'telaag') {
            $buffer['foutmelding'] = "Het bod is te laag";
        }
    }

    $bidhistory = getBidHistory($auction);
    $buffer['titel'] = $iteminfo['titel'];
    $buffer['startprijs'] = $iteminfo['startprijs'];
    $buffer['beschrijving'] = $iteminfo['beschrijving'];
    $buffer['betalingswijze'] = $iteminfo['betalingswijze'];
    $buffer['plaatsnaam'] = $iteminfo['plaatsnaam'];
    $buffer['land'] = $iteminfo['land'];
    $buffer['verkoper'] = $iteminfo['verkoper'];
    $buffer['verzendinstructies'] = $iteminfo['verzendinstructies'];
    $buffer['eindmoment'] = str_replace(" ","T",$iteminfo['looptijdeindmoment']->format('Y-m-d H:i:s'));
    $buffer['gesloten'] = $iteminfo['gesloten'];
    $buffer['laatstebod'] = $iteminfo['gesloten'];
    $buffer['verzendkosten'] = $iteminfo['verzendkosten'];
    $buffer['bedrag'] = hoogsteBod($iteminfo, $bidhistory);
    $buffer['voorwerpnummer'] = $iteminfo['voorwerpnummer'];
    $buffer['beoordeling'] = DatabaseTools::getBeoordelingStars($iteminfo['verkoper']);
    $buffer['beschrijvingsrc'] = "inc/body.php?veiling=" . $iteminfo['voorwerpnummer'];
    $buffer['categorie1'] = getCategorie($auction)['categorie1'];
    $buffer['categorie2'] = getCategorie($auction)['categorie2'];
    $buffer['minimalebedrag'] = sprintf('%01.2f', minimalebedrag($auction));

    if ($iteminfo['verzendkosten'] == null) {
        $buffer['verzendkosten'] = $iteminfo['verzendkosten'];
    } else {
        $buffer['verzendkosten'] = "geen";
    }

    if (ImageProvider::getImagesForAuction($iteminfo['voorwerpnummer'])->getImageCount() == 0) {
        $buffer['pic'] .= '<img src="img/logo_header.png" alt="geen foto" class="img-thumbnail">';
    } else {
        for ($count = 0; $count < ImageProvider::getImagesForAuction($iteminfo['voorwerpnummer'])->getImageCount(); $count++) {
            if ($count == 0) {
                $col = 12;
            } else {
                $col = 4;
            }
            $image = ImageProvider::getImagesForAuction($auction)->getImage($count);
            $buffer['pic'] .= <<<"END"
    <div class="col-md-$col kleine-thumbnail col-xs-$col">
                    <a href="#" data-toggle="modal" data-target="#basicModal$count">
                        <img src="$image" alt="geen foto" class="img-thumbnail">
                    </a>                </div>
                <div class="modal fade" id="basicModal$count" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">$iteminfo[titel]</h5>
                            </div>
                            <div class="modal-body">
                                <img src="$image" alt="geen foto" class="img-thumbnail">
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

function minimalebedrag($auction){
    $iteminfo = getItemInfo($auction);
    $bidhistory = getBidHistory($auction);
    $hoogstebod = hoogsteBod($iteminfo, $bidhistory);
    return $hoogstebod + getMinimumVerhoging($hoogstebod);
}

function post() {
    global $buffer;
    global $DB;
    if (!isset($_POST['submit'])) {
        header("Location: index.php?page=product&veiling=$_GET[veilingnummer]");
        exit();
    }
    if ($_POST['submit'] == "bodplaatsen") {
        $auction = $_POST['veiling'];
        //var_dump($_POST);
        $iteminfo = getItemInfo($auction);
        $bidhistory = getBidHistory($auction);


        if ($_SESSION['username'] == null || $_SESSION['username'] == "") {
            header("Location: index.php?page=inloggen");
            exit;
        }
        if ($_SESSION['username'] == $iteminfo['verkoper']) {
            header("Location: index.php?page=product&veiling=$auction&err=nieteigen");
            exit;
        }
        $hoogstebod = hoogsteBod($iteminfo, $bidhistory);
        if ($_POST['bodInvoer'] >= ($hoogstebod + getMinimumVerhoging($hoogstebod))) {
            bodPlaatsen($_POST['bodInvoer'], $_SESSION['username'], $iteminfo['voorwerpnummer']);
        } else {
            header("Location: index.php?page=product&veiling=$auction&err=telaag");
            exit;
        }
        header("Location: index.php?page=product&veiling=$auction");
        exit;
    } else if ($_POST['submit'] == "stelvraag") {
        //mail
        $body = "Een gebruiker heeft u een vraag gesteld: <br/>" . $_POST['gesteldevraag'] . "<br/> Deze vraag werd gesteld door: " . $_POST['mailadres'] ;
        $subject = "Eenmaal Andermaal: $_POST[mailadres] stelt u een vraag";
        //selecteer mailadres van de verkoper van deze veiling
        $tsql = "SELECT mailbox
                      FROM Voorwerp JOIN gebruiker
                      ON gebruiker.gebruikersnaam = voorwerp.verkoper
                      WHERE voorwerpnummer = ?" ;
        $params = array($_GET['veiling']);
        $stmt = sqlsrv_query($DB, $tsql, $params);
        $mailadress = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['mailbox'];

        require inc . 'mail.php';
        sendMail($mailadress, $subject, $body);
    }
    header("Location: index.php?page=product&veiling=$_GET[veiling]");
    exit();

}

function bodPlaatsen($bod,$gebruiker,$veiling){
    global $DB;
    $sql = "INSERT INTO Bod (voorwerpnummer, gebruikersnaam, bodbedrag )
            VALUES (?, ?, ?)";
    $params = array ($veiling, $gebruiker, $bod);
    sqlsrv_query($DB, $sql, $params);
    var_dump(sqlsrv_errors());
}

function hoogsteBod($iteminfo, $bidhistory){

    return isset($bidhistory[0]) ? $bidhistory[0]['bodbedrag'] : $iteminfo['startprijs'];
}

function getMinimumVerhoging($huidigeprijs)
{

    if ($huidigeprijs >= 5000) {
        return 50.00;
    } else if ($huidigeprijs >= 1000) {
        return 10.00;
    } else if ($huidigeprijs >= 500) {
        return 5.00;
    } else if ($huidigeprijs >= 50) {
        return 1.00;
    } else if ($huidigeprijs >= 1) {
        return 0.50;
    }
}

function bodControle($itemInfo,$bidhistory, $bod){
    $hoogstebod = hoogsteBod($itemInfo, $bidhistory);
    if ($bod >= $hoogstebod + getMinimumVerhoging($hoogstebod)) {
        return true;
    }
    else {
        return  $buffer['error']=1;
    }
}

function getBidHistory($auction) {
    global $DB;
    $tsql = "SELECT bodbedrag, gebruikersnaam, datumtijd FROM Bod WHERE voorwerpnummer = ? ORDER BY bodbedrag DESC;";
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
    $tsql = "SELECT voorwerpnummer, titel, beschrijving, startprijs, betalingswijze, betalingsinstructie, plaatsnaam, land,
            verzendinstructies, verkoper, looptijdeindmoment, gesloten, verzendkosten FROM Voorwerp WHERE voorwerpnummer = ?;";
    $params = array($auction);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

function getCategorie($auction) {
    global $DB;
    $tsql = "SELECT Rubriek.rubrieknaam
             FROM Rubriek INNER JOIN Voorwerpinrubriek ON Rubriek.rubrieknummer = Voorwerpinrubriek.rubrieknummer
             WHERE Voorwerpinrubriek.voorwerpnummer=?;";
    $params = array($auction);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $categorie = array();
    $categorie['categorie1'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['rubrieknaam'];
    $categorie['categorie2'] = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)['rubrieknaam'];
    return $categorie;
}


