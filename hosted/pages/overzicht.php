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
    //Latu
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
    $sorteer = array();

    if (isset($_GET['sorteerPrijs']) && isset($_GET['looptijd'])) {
        $sorteer['bodbedrag'] = $_GET['sorteerPrijs'];
        $sorteer['looptijd'] = $_GET['looptijd'];
    }
    else {
        $sorteer['bodbedrag'] = 'asc';
        $sorteer['looptijd'] = '';
    }
    if (isset($_GET['search']) && isset($_GET['category'])) {
        $search = $_GET['search'];
        $cat = $_GET['category'];
    }
    doSearch($search, $cat, $sorteer);

    $buffer['search'] = isset($_GET['search']) ? $_GET['search'] : "";
    $buffer['action'] = isset($_GET['action']) ? $_GET['action'] : "search";
    $buffer['category'] = isset($_GET['category']) ? $_GET['category'] : "";
    setCategories(!isset($_GET['category']) ? -1 : $_GET['category']);
}


function doSearch($searchvalue, $category, $sortvalue)
{
    global $buffer;
    global $DB;
    $wayofsorting = array();
    $wayofsorting = $sortvalue;
    if (isset($wayofsorting)) {
        if (strlen($wayofsorting['bodbedrag']) > 0) {
            $wayofsorting['way'] = 'bodbedrag';
            $wayofsorting['how'] = $wayofsorting['bodbedrag'];
        } else if (strlen($wayofsorting['looptijd']) > 0) {
            $wayofsorting['way'] = 'looptijd';
            $wayofsorting['how'] = $wayofsorting['looptijd'];
        }
    }
        if ($category == null) {
            var_dump('test1');
            $tsql = <<<END
            SELECT top 10 v.voorwerpnummer, V.titel, V.beschrijving, bodbedrag = (
                  SELECT TOP 1 bodbedrag
                  FROM bod
                  WHERE bod.voorwerpnummer = V.voorwerpnummer
                  ORDER BY bodbedrag DESC
            )
            FROM Voorwerp V
              INNER JOIN Voorwerpinrubriek VR
                ON V.voorwerpnummer = VR.voorwerpnummer
            WHERE V.Titel LIKE (?)
            ORDER BY
END;
             $tsql .= " bodbedrag DESC";
            //$wayofsorting['way'] == "bodbedrag" ? "bodbedrag" : "looptijd" . $wayofsorting['how'] == "asc" ? "ASC;" : "DESC;";
            $params = array('%' . $searchvalue . '%');
            var_dump($tsql);
        } else {
            var_dump('test2');
            $tsql = <<<END
            SELECT v.voorwerpnummer, V.titel, V.beschrijving, bodbedrag = (
                  SELECT TOP 1 bodbedrag
                  FROM bod
                  WHERE bod.voorwerpnummer = V.voorwerpnummer
                  ORDER BY bodbedrag DESC
            )
            FROM Voorwerp V
              INNER JOIN Voorwerpinrubriek VR
                ON V.voorwerpnummer = VR.voorwerpnummer
            WHERE V.Titel LIKE (?)
            AND dbo.fnWelkeCatIsHoofd(rubrieknummer) = ?
            ORDER BY
END;
            $tsql .= " bodbedrag DESC";
            $params = array('%' . $searchvalue . '%', $category);
        }
    var_dump(sqlsrv_errors($tsql));
        $stmt = sqlsrv_query($DB, $tsql, $params);
    var_dump(sqlsrv_errors($stmt));
        //if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $titel = $row['titel'];
                //$beschrijving = $row['beschrijving'];
                $bodbedrag = $row['bodbedrag'];
                $veiling = $row['voorwerpnummer'];
                //$beoordeling = DatabaseTools::getBeoordelingStars($row['gebruikersnaam']);

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
                        <p>beoordeling</p>
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
                    Klik op veiling voor meer info.
                </p>

                <a href="#" class="btn btn-warning btn-lg">Snel bieden</a>
                <a href="index.php?page=product&veiling=$veiling" class="btn btn-primary btn-lg">Toon veiling</a>
            </div>
        </div>
END;

                $buffer['veilingen'] .= $template;
            }
       // }
    }

    function setCategories($active)
    {
        global $DB, $buffer;
        $list = Category::getCatList($active);
        $buffer['acdn'] .= getHTMLForSub(-1, $list, 0);
    }

    function getHTMLForSub($cat, $list, $count)
    {
        $category = Category::getCategory($cat);
        $output = "<ul>";
        foreach ($category as $value) {
            $level = getLevel($count);
            $link = "?page=overzicht&category=" . $value['rubrieknummer'];
            $output .= "<li class='$level'>" . "<a href='$link'>" . $value['rubrieknaam'] . "</a>";

            if (isset($list[$count]) && $value['rubrieknummer'] == $list[$count]['rubrieknummer']) {
                $output .= getHTMLForSub($value['rubrieknummer'], $list, $count + 1);
            } else {
                $output .= "<ul></ul>";
            }
            $output .= "</li>";
        }

        $output .= "</ul>";
        return $output;

    }

    function getLevel($int)
    {
        switch ($int) {
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