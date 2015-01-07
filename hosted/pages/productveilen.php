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
    $buffer['hoofdcategorien'] = "";

}

function get() {
    global $buffer;
    if(!isset($_SESSION['username'])|| $_SESSION['username'] == null) {
        header('Location: index.php');
        exit();
    }
    if (!isSeller($_SESSION['username'])) {
        header('Location: ?page=mijnaccount');
    }

    $categories = Category::getCategory(-1);
    foreach ($categories as $id => $value) {
        $name = $value['rubrieknaam'];
        $buffer['hoofdcategorien'] .= "<option value='$id'>$name</option>";
    }
}

function post() {
    global $buffer;

}