<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 11-12-2014
 * Time: 13:30
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
    $buffer['veilingen'] = "";

}

function get() {
    global $buffer;
    $search = '';
    $cat = null;
    if (isset($_GET['search']) && isset($_GET['category'])) {
        $search = $_GET['search'];
        $cat = $_GET['category'];
    }


    doSearch($search, $cat]);
}

function post() {
    global $buffer;

}


function doSearch($searchvalue, $category) {
    global $buffer;
    global $DB;
    if ($category == null && $searchvalue == '') {
        $tsql = <<<"END"
        declare @zoekterm VARCHAR(MAX)= ?

    SELECT v.voorwerpnummer, V.titel, V.beschrijving,
    bodbedrag =     (SELECT TOP 1 bodbedrag FROM bod
                     WHERE bod.voorwerpnummer = V.voorwerpnummer
                     ORDER BY bodbedrag DESC)
    FROM Voorwerp V INNER JOIN Voorwerpinrubriek VR
        ON V.voorwerpnummer = VR.voorwerpnummer
    WHERE V.Titel LIKE '%'+@zoekterm+'%'
END;
    }

    $tsql = <<<"END"
    declare @zoekterm VARCHAR(MAX)= ?

    SELECT v.voorwerpnummer, V.titel, V.beschrijving,
    bodbedrag =     (SELECT TOP 1 bodbedrag FROM bod
                     WHERE bod.voorwerpnummer = V.voorwerpnummer
                     ORDER BY bodbedrag DESC)
    FROM Voorwerp V INNER JOIN Voorwerpinrubriek VR
        ON V.voorwerpnummer = VR.voorwerpnummer
    WHERE V.Titel LIKE '%'+@zoekterm+'%'
    AND dbo.fnWelkeCatIsHoofd(rubrieknummer) = ?;
END;

    $params = array($searchvalue, $category);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $titel = $row['titel'];
        var_dump($row);
        $beschrijving = $row['beschrijving'];
        $bodbedrag = $row['bodbedrag'];

        $template = <<<"END"
        <div class="col-md-8 panel panel-info panel-body bod-gegevens col-xs-8">

            <div class="col-md-4 col-xs-4">
                <img src="img/audi.jpg" alt="audi" class="img-rounded img-responsive">

                <div class="col-md-12 gegevens-product col-xs-12">
                    <div class="col-md-3 col-xs-3">
                        <img src="img/klok.png" alt="audi">
                    </div>
                    <div class="col-md-9 timer col-xs-9">
                        Dagen:
                    </div>
                    <div class="col-md-9 timer col-xs-9">
                        Tijd:
                    </div>
                </div>
                <div class="col-md-12 gegevens-product col-xs-12">
                    <div class="col-md-12 col-xs-12">
                        <p>Beoordeling verkoper</p>
                    </div>
                    <div class="col-md-12 col-xs-12">

                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xs-8">

                <div class="panel-heading-product">
                    <h3 class="panel-title">$titel</h3>

                </div>

                <div class="panel-heading-product-afbeelding laatstebod">

                    <h3 class="panel-title">Laatste bod: $bodbedrag</h3>
                </div>
                <h3>Product informatie</h3>

                <p>
                    $beschrijving
                </p>

                <a href="#" class="btn btn-warning btn-lg">Snel bieden</a>
                <a href="#" class="btn btn-primary btn-lg">Toon veiling</a>
            </div>
        </div>
END;

    $buffer['veilingen'] .= $template;
    }
}


