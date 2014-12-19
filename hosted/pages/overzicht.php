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
   
}

function post() {
    global $buffer;

}

function getAllCategories() {
    global $DB;
    $content = "";
    //Eerste categorie
    var_dump(getCategory(-1));
    foreach (getCategory(-1) as $key => $value) {
        $content .= "<li class='level-one'>" . $value['rubrieknaam'];
        //Eerste Sub

    }
    return $content;
}

function getCategory($id) {
    global $DB;
    $category = array();

    if ($id == -1) {
        $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek IS NULL ORDER BY volgnummer, rubrieknaam;";
        $stmt = sqlsrv_query($DB, $sql, array());
    } else {
        $sql = "SELECT rubrieknaam, rubrieknummer, ouderrubriek, volgnummer FROM Rubriek WHERE ouderrubriek = ? ORDER BY volgnummer, rubrieknaam;";
        $stmt = sqlsrv_query($DB, $sql, array($id));
    }

    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $category[$row['rubrieknummer']] = array (
            "rubrieknaam" => $row['rubrieknaam'],
            "rubrieknummer" => $row['rubrieknummer'],
            "ouderrubriek" => $row['ouderrubriek'],
            "volgnummer" => $row['volgnummer']
        );
    }
    return $category;
}

function hasSubs($id) {
    global $DB;
    $sql = "SELECT * FROM Rubriek WHERE ouderrubriek = ?;";
    $stmt = sqlsrv_query($DB, $sql, array($id));
    if (!$stmt || !sqlsrv_has_rows($stmt)) {
        return false;
    } else {
        return true;
    }
}


function get() {
    global $buffer;
    $search = '';
    $cat = null;
    if (isset($_GET['search']) && isset($_GET['category'])) {
        $search = $_GET['search'];
        $cat = $_GET['category'];
    }
    doSearch($search, $cat);
}


function doSearch($searchvalue, $category) {
    global $buffer;
    global $DB;
    if ($category == null) {
        $tsql = "SELECT v.voorwerpnummer, V.titel, V.beschrijving, bodbedrag = (
                  SELECT TOP 1 bodbedrag
                  FROM bod
                  WHERE bod.voorwerpnummer = V.voorwerpnummer
                  ORDER BY bodbedrag DESC
            )
            FROM Voorwerp V
              INNER JOIN Voorwerpinrubriek VR
                ON V.voorwerpnummer = VR.voorwerpnummer
            WHERE V.Titel LIKE (?);";
        $params = array('%' . $searchvalue . '%');
    } else {
        $tsql = "SELECT v.voorwerpnummer, V.titel, V.beschrijving, bodbedrag = (
                SELECT TOP 1 bodbedrag
                FROM bod
                WHERE bod.voorwerpnummer = V.voorwerpnummer
                ORDER BY bodbedrag DESC
            )
            FROM Voorwerp V
              INNER JOIN Voorwerpinrubriek VR
                ON V.voorwerpnummer = VR.voorwerpnummer
            WHERE V.Titel LIKE (?)
            AND dbo.fnWelkeCatIsHoofd(rubrieknummer) = ?;";
        $params = array('%' . $searchvalue . '%', $category);
    }
    $stmt = sqlsrv_query($DB, $tsql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $titel = $row['titel'];
        var_dump($row);
        $beschrijving = $row['beschrijving'];
        $bodbedrag = $row['bodbedrag'];
        $veiling = $row['voorwerpnummer'];

        $template = <<<"END"
        <div class="col-md-8 panel panel-info product-overzicht panel-body bod-gegevens col-xs-8">

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
                <a href="index.php?page=product&veiling=$veiling" class="btn btn-primary btn-lg">Toon veiling</a>
            </div>
        </div>
END;

    $buffer['veilingen'] .= $template;
    }
}
