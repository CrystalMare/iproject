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
    $buffer['vragen'] = "";
    $buffer['days'] = "";
    $buffer['months'] = "";
    $buffer['years'] = "";
    $buffer['countries'] = "";
    $buffer['key'] = $_GET['key'];
    $buffer['error'] = "";

}

function get() {
    global $buffer, $DB;
    if (!isset($_SESSION['register']) || !isset($_GET['key']) || $_GET['key'] != $_SESSION['register']['key'] || $_GET['key'] != $_SESSION['register']['key']) {
        header(307, "Location: index.php?page=registreren");
        exit();
    }

    $sql = "SELECT vraagnummer, vraag FROM Vraag;";

    $stmt = sqlsrv_query($DB, $sql);
    if (!$stmt)
        die(print_r(sqlsrv_errors()));
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $buffer['vragen'] .= "<option value='" . $row['vraagnummer'] . "'>" . $row['vraag'] . "</option>\n";
    }

    if (isset($_GET['err'])) {
        switch($_GET['err']) {
            case 'pw_notsame':
                $buffer['error'] = "Wachtwoorden komen niet overeen.";
                break;
            case 'user_exists':
                $buffer['error'] = "Gebruikersnaam bestaat al.";
                break;
            case 'pw_short':
                $buffer['error'] = "Wachtwoord te kort.";
                break;
            case 'key_error':
                $buffer['error'] = "Registratie sessie verlopen.";
                break;
            case 'error':
                $buffer['error'] = "Er is iets mis gegaan, probeer het opnieuw.";
                break;
            default:
                $buffer['error'] = "Controleer uw invoer.";
                break;
        }
    }

    //Landen
    $sql = "SELECT landnaam FROM Land ORDER BY landnaam ASC";
    $stmt = sqlsrv_query($DB, $sql);
    if (!$stmt) {
        die(print_r(sqlsrv_errors()));
    }
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $country = $row['landnaam'];
        if ($country == "Netherlands") {
                $buffer['countries'] .= "<option selected='selected' value='$country'>$country</option>";
        } else {
            $buffer['countries'] .= "<option value='$country'>$country</option>";
        }
    }

    //Days
    for ($i = 1; $i <= 31; $i++) {
        if ($i < 10) {
            $buffer['days'] .= "<option value='0$i'>0$i</option>";
        } else {
            $buffer['days'] .= "<option value='$i'>$i</option>";
        }
    }
    //Months
    for ($i = 1; $i <= 12; $i++) {
        if ($i < 10) {
            $buffer['months'] .= "<option value='0$i'>0$i</option>";
        } else {
            $buffer['months'] .= "<option value='$i'>$i</option>";
        }

    }
    //Years
    for ($i = 2014; $i >= 1900; $i--) {
        $buffer['years'] .= "<option value='$i'>$i</option>";
    }

}
function post() {
    global $buffer;
    get();
}

