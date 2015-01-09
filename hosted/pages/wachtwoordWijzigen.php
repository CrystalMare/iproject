<?php

setDefaultBuffer();
$_SESSION['username'] = "";

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

function get() {
    global $buffer, $DB;
    $username = $_SESSION['restore'];

    if(!checkSession()) {
        header("Location: index.php?page=index");
    }



}

function post() {
    global $buffer, $DB;

    if($_POST['wachtwoord'] != "" && $_POST['herhaalWachtwoord'] != "" && $_POST['wachtwoord'] == $_POST['herhaalWachtwoord'] && strlen($_POST['wachtwoord']) > 6)
    {
        $newPassword = $_POST['herhaalWachtwoord'];
        $value = changePassword($newPassword, $_SESSION['restore']);
        $buffer['status'] = $value ? "Je wachtwoord is aangepast homo" : " er is iets misgegaan stop met internetten!";
    }

}
function changePassword($new, $user)
{
    global $DB;
    $tsql = "SELECT salt FROM Gebruiker WHERE gebruikersnaam = ?;";
    $stmt = sqlsrv_query($DB, $tsql, array($user));
    $salt = sqlsrv_fetch_array($stmt)['salt'];
    $hash = hash('sha256', $new . $salt);
    $tsql = "UPDATE Gebruiker SET wachtwoord = ? WHERE gebruikersnaam = ?;";
    $stmt = sqlsrv_query($DB, $tsql, array($hash, $user));
    return sqlsrv_errors() == NULL;
}

function checkSession()
{
    if(!isset($_SESSION['restore']) && $_SESSION['restore'] == "")
    {
        return false;
    }
    else {
        return true;
    }
}
