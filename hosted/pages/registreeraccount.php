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
}

function get() {
    global $buffer, $CONFIG, $DB;
    if (!isset($_SESSION['register']) || !isset($_GET['key']) || $_GET['key'] != $_SESSION['register']['key'] || $_GET['key'] != $_SESSION['register']['key']) {
        header("Location: ?page=registreren");
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

}
function post() {
    global $buffer;
    get();
}

