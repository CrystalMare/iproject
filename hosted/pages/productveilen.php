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
        header('Location: index.php');
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

function test() {
    $URL = "HAHA";
    $teststring = <<<"END"
            <div class="col-md-6 col-xs-6">
                <div class="form-group">
                    <div class="col-lg-9">
                        <select name="category1sub2"class="form-control" id="category1sub2">
                        </select>
                        <br>
                        <img src='$URL'>
                    </div>
                </div>
            </div>

END;
    echo $teststring;




}