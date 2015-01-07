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
    $buffer['meldingbevestigingscode']="";

}

function get() {
    global $buffer;

}

function post() {
    global $buffer;
    var_dump($_POST);
    if(isset($_POST['bevestigingscodeverkoper'])){
        $buffer['meldingbevestigingscode']="Voer alle velden in";
    }
}