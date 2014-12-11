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

    require(inc . 'mail.php');
    echo sendCode($_POST['email']);

    $buffer['emailerror'] = "Er is een email verstuurd naar " . $_POST['email'];

    return;
}