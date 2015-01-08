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
    global $buffer, $DB;


}

function get() {

    global $buffer, $DB;

    $email = 'justinklaassenbusiness@gmail.com';
    $sql = "select vraag
    from vraag where vraagnummer in (select vraag from gebruiker where mailbox = '$email')";


    $params = array($email);
    $stmt = sqlsrv_query($DB, $sql);
    //HIER MOET HET EMAILADRES VAN DE GEBRUIKER OPGEHAALD WORDEN

    $vraag = sqlsrv_fetch_array($stmt);
    var_dump($vraag);

    $buffer['vraag'] = $vraag;
    var_dump($buffer['vraag']);
    //$buffer['vraag'] = 'gay';



}

function post() {
    global $buffer;

}