<?php
/**
 * Created by PhpStorm.
 * User: Hideki
 * Date: 08/01/15
 * Time: 15:21
 */

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

}