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

}

function get() {
    global $buffer;

}
function post()
{
    global $buffer;
    var_dump($_POST);
    if (!isset($_POST['passwordrecovery']) || $_POST['passwordrecovery'] == "") {
        $buffer['emailerror'] = "Voer een geldig emailadres in";
        return;
    }
    if (!filter_var($_POST['passwordrecovery'], FILTER_VALIDATE_EMAIL)) {
        $buffer['emailerror'] = "Dit is geen geldig emailadres";
        return;
    }
    return;
}

    require(inc . 'mail.php');

    if (sendCode($_POST['passwordrecovery']) == 1)
    {
        $buffer['emailerror'] = "Er is een email verstuurd naar " . $_POST['passwordrecovery'];
        header("Location: index.php?page=wachtwoordcode");
    } else {
        $buffer['emailerror'] = "Er is iets mis gegaan. Probeer het nog een keer.";
    }
    return;
