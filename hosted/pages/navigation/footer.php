<?php

setDefaultFooterBuffer();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getFooter();
        break;
    case 'POST':
        postFooter();
        break;
    default:
        getFooter();
}

function setDefaultFooterBuffer() {
    global $buffer;

}

function getFooter() {
    global $buffer;

}

function postFooter() {
    global $buffer;

}