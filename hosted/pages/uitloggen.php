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
    $buffer["test"] = '';

}

function get() {
    global $buffer;
    $_SESSION['username'] = null;
    header('Location: index.php');
}

function post() {
    global $buffer;

}