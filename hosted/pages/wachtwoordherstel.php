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
    var_dump($_POST['email']);
    if (!isset($_POST['email']) || $_POST['email'] == "") {
        $buffer['emailerror'] = "Voer een geldig emailadres in";
        return;
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $buffer['emailerror'] = "Dit is geen geldig emailadres";
        return;
    }
    return;
}

    require(inc . 'mail.php');

    if (sendCode($_POST['email']) == 1)
    {
        $buffer['emailerror'] = "Er is een email verstuurd naar " . $_POST['email'];
        header("Location: index.php?page=wachtwoordcode");
    } else {
        $buffer['emailerror'] = "Er is iets mis gegaan. Probeer het nog een keer.";
    }
    return;
