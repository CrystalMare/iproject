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
    $buffer['category'] = "";
    $buffer['action'] = "search";
    $buffer['search'] = "";
    $buffer['acdn'] = "";
    $buffer['counters'] = "";

}

function post() {
    global $buffer;

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
    $search = isset($_GET['search']) ? $_GET['search'] : "";
    $cat = isset($_GET['category']) ? $_GET['category'] : null;
    $cat = $cat == "" ? null : $cat;

    $sorteerprijs = isset($_GET['sorteerPrijs']) ? $_GET['sorteerPrijs'] : "asc";
    $looptijd = isset($_GET['looptijd']) ? $_GET['looptijd'] : "asc";

    if ($sorteerprijs != "asc") {
        $sort = "startprijs DESC";
    } else {
        $sort = "startprijs ASC";
    }

    if ($sorteerprijs == "asc" && $looptijd != "asc") {
        $sort = "looptijdeindmoment DESC";
    } else {
        $sort = "Voorwerp.voorwerpnummer DESC";
    }

    doSearch($search, $cat, $sort);

    $buffer['search'] = isset($_GET['search']) ? $_GET['search'] : "";
    $buffer['action'] = isset($_GET['action']) ? $_GET['action'] : "search";
    $buffer['category'] = isset($_GET['category']) ? $_GET['category'] : "";
    setCategories(!isset($_GET['category']) ? -1 : $_GET['category']);
}


function doSearch($searchvalue, $category, $order) {
    global $buffer, $DB;

    $categoryfilter = is_null($category) ? "" : "AND dbo.fnIsSub(Voorwerpinrubriek.rubrieknummer, ?, 0) = 1";

    $tsql = "
        SELECT DISTINCT TOP 10 Voorwerp.voorwerpnummer, Voorwerp.looptijdeindmoment, Voorwerp.verkoper, Voorwerp.titel,
          Voorwerp.startprijs, Voorwerp.beschrijving, bodbedrag = (
          SELECT TOP 1                                bodbedrag
          FROM Bod
          WHERE Bod.voorwerpnummer = Voorwerp.voorwerpnummer
          ORDER BY bodbedrag DESC
        )
        FROM Voorwerp
          JOIN Voorwerpinrubriek
            ON Voorwerp.voorwerpnummer = Voorwerpinrubriek.voorwerpnummer
        WHERE titel LIKE (?) AND gesloten = 0 $categoryfilter
        ORDER BY $order;";

    $params = is_null($category) ? array("%$searchvalue%") : array("%$searchvalue%", $category);

    $stmt = sqlsrv_query($DB, $tsql, $params);
    $count = 0;

    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $titel = $row['titel'];
        $voorwerpnummer = $row['voorwerpnummer'];
        $bodbedrag = "&#8364;" . (($row['bodbedrag'] == null) ? $row['startprijs'] : $row['bodbedrag']);
        $veiling = $row['voorwerpnummer'];
        $img = ImageProvider::getImagesForAuction($row['voorwerpnummer'])->getImage(0);
        $buffer['beoordeling'] = DatabaseTools::getBeoordelingStars($row['verkoper']);
        $beschrijving = "inc/body.php?veiling=" . $row['voorwerpnummer'];

        $template = <<<"END"
        <div class="col-md-8 panel panel-info product-overzicht panel-body bod-gegevens col-xs-8">

            <div class="col-md-4 col-xs-4">
                <img src="$img" alt="audi" class="img-rounded img-responsive img-productoverzicht">

                <div class="col-md-12 gegevens-product col-xs-12">
                    <div class="col-md-3 col-xs-3">
                        <img src="img/klok.png" alt="audi">
                    </div>
                    <div class="col-md-9 timer col-xs-9">
                        Tijd tot einde veiling:
                    </div>
                    <div class="col-md-9 timer col-xs-9">
                        <span id="tijdover$count"></span>
                    </div>
                </div>
                <div class="col-md-12 gegevens-product col-xs-12">
                    <div class="col-md-12 col-xs-12">
                        <p>Beoordeling Verkoper: %beoordeling%</p>
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
                    <h3> Product informatie </h>
                <p>
                    <iframe src="$beschrijving" class="iFrame">
                    Geen beschrijving gevonden.
                    </iframe>
                </p>
                <div class="col-md-12 knoppen-snelentoon col-xs-12">

                <button type="button" onclick="sure($veiling);" class="btn btn-warning btn-lg">Snel bieden</a>
                <a href="index.php?page=product&veiling=$veiling" class="btn btn-primary btn-lg">Toon veiling</a>
                </div>
            </div>
        </div>

END;


        $buffer['veilingen'] .= $template;
        $timeend = str_replace(" ", "T", $row['looptijdeindmoment']->format('Y-m-d H:i:s'));
        $buffer['counters'] .= "getCount(new Date('$timeend'), 'tijdover$count');";
        $count++;
    }

}

function setCategories($active) {
    global $DB, $buffer;
    $list = Category::getCatList($active);
    $buffer['acdn'] .= getHTMLForSub(-1, $list, 0);
}

function getHTMLForSub($cat, $list, $count) {
    $category = Category::getCategory($cat);
    $output = "<ul>";
    foreach($category as $value) {
        $level = getLevel($count);
        $link = "?page=overzicht&category=" . $value['rubrieknummer'];
        $output .= "<li class='$level'>" . "<a href='$link'>" . $value['rubrieknaam'] . "</a>";

        if (isset($list[$count]) &&     $value['rubrieknummer'] == $list[$count]['rubrieknummer']) {
            $output .= getHTMLForSub($value['rubrieknummer'], $list, $count + 1);
        } else {
            $output .= "<ul></ul>";
        }
        $output .= "</li>";
    }

    $output .= "</ul>";
    return $output;

}

function getLevel($int) {
    switch($int) {
        case 0:
            return "level-one";
        case 1:
            return "level-two";
        case 2:
            return "level-three";
        case 3:
            return "level-four";
        case 4:
            return "level-five";
        default:
            return "";
    }
}