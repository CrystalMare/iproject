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

    $buffer['veilingen'] = "";
    $buffer['script'] = "";
}

function get() {
    global $buffer, $DB;

    $tsql = "SELECT TOP 10 voorwerpnummer, titel, startprijs, looptijdeindmoment, hoogstebod = (
                  SELECT TOP 1 bodbedrag
                  FROM bod
                  WHERE bod.voorwerpnummer = Voorwerp.voorwerpnummer
                  ORDER BY bodbedrag DESC
                  )
              FROM Voorwerp;";
    $stmt = sqlsrv_query($DB, $tsql);
    var_dump(sqlsrv_errors());
    $count = 0;
    var_dump(sqlsrv_num_rows($stmt));
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $titel = $row['titel'];
        $veilingid = $row['voorwerpnummer'];
        $picurl = ImageProvider::getImagesForAuction($row['voorwerpnummer'])->getImage(0);
        $prijs = $row['hoogstebod'] == null ? $row['startprijs'] : $row['hoogstebod'];


        $buffer['veilingen'] .= <<<END
            <div class="col-md-3 col-xs-3">
        <div class="panel panel-default">
            <div class="panel-heading ea-artikel-title " id="aanpassen">$titel</div>
            <div class="panel-body home-artikelen ea-home-body">
                <img src="$picurl" alt="$titel" class="img-responsive home-body-artikelen nieuw">
                <a href="?page=product&veiling=$veilingid" class="btn btn-info bekijk-artikel">Bekijk artikel</a>
            </div>
            <div class="panel-heading ea-artikel-footer">&#8364;$prijs &#124; <span id="timer$count"></span></div>
        </div>
    </div>
END;
        $date = str_replace(" ", "T", $row['looptijdeindmoment']->format("Y-m-d h:i:s"));
        $buffer['script'] .= "getCount(new Date('$date'), 'timer$count');\n";
        $count++;
    }
}

function post() {
    global $buffer;

}