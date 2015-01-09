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
    $buffer['emailerror'] = "";
}

function get() {

}

function post() {
    global $buffer;
    if (!isset($_POST['email'])) {
        $buffer['emailerror'] = "Voer een geldig emailadres in";
        return;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $buffer['emailerror'] = "Dit is geen geldig emailadres";
        return;
    }
    if (checkExistingEmail($_POST['email']))
    {
        $buffer['emailerror'] = "Dit emailadres bestaat al!";
        return;
    }


    require(inc . 'mail.php');

    if (sendCode($_POST['email']) == 1)
    {
        $buffer['emailerror'] = "Er is een email verstuurd naar " . $_POST['email'];
        header("Location: index.php?page=activeren&status=sent");
    } else {
        $buffer['emailerror'] = "Er is iets mis gegaan. Probeer het nog een keer.";
    }
    return;
}

function checkExistingEmail($email)
{
    global $DB;
    $tsql = "SELECT * FROM gebruiker WHERE mailbox = ?;";
    $params = array($email);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($email == $result['mailbox']) {
        return true;
    }
    else {
        return false;
    }




}
