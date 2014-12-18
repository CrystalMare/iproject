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

function post() {
    global $buffer;
    var_dump($_POST);
    var_dump($_FILES);

    //Validation
    $item = array();
    $item['titel'] = $_POST['artikelnaam'];
    $item['beschrijving'] = $_POST['beschrijving'];
}