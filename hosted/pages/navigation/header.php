<?php
/**
 * Created by PhpStorm.
 * User: Sven
 * Date: 11-12-2014
 * Time: 10:01
 */


setDefaultHeaderBuffer();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getHeader();
        break;
    case 'POST':
        postHeader();
        break;
    default:
        getHeader();
}

function setDefaultHeaderBuffer() {
    global $buffer;


}

function getHeader() {
    global $buffer;
    if(!isset($_SESSION['username'])|| $_SESSION['username'] == null) {
        $buffer['menu1'] = "registreren";
        $buffer['menu11'] = "Registreren";
        $buffer['menu2'] = "inloggen";
        $buffer['menu22'] = "Inloggen";
    } else {
        $buffer['menu1'] = "mijnaccount";
        $buffer['menu11'] = "Mijn account";
        $buffer['menu2'] = "uitloggen";
        $buffer['menu22'] = "Uitloggen";
    }
}

function postHeader() {
    global $buffer;

}