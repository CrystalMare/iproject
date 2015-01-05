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

    if(isset($_POST['identificatiemethode'])) {
        if ($_POST['identificatiemethode'] == 'Creditcard') {

        } else if ($_POST['identificatiemethode'] == 'Post') {
            if(isset($_POST['']))
        }
    }
}