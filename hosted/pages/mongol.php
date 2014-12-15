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
    $buffer["test"] = 'Hello penis';

}

function post() {
    global $buffer;

}