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
    $buffer['status'] = "";
}

function checkKey($key, $email, $time) {
    $hash = hash("sha256", $email . $time);
    if ($hash == $key) return true;
    return false;
}

function get() {
    global $buffer;
    if (!isset($_GET['key']) || !isset($_GET['date']) || !isset($_GET['email'])) {
        $buffer['status'] = "Dit is geen volledige code";
        return;
    }
    else {
        $code = $_GET['key'];
        $date = $_GET['date'];
        $email = $_GET['email'];

        if (intval($date) > (time() + (60*60*4))) {
            $buffer['status'] = "Deze code is verlopen en niet meer geldig.";
        }
        else {
            if (checkKey($code, $email, $date)) {
                $_SESSION['register']['email'] = $email;
                $_SESSION['register']['date'] = $date;
                $_SESSION['register']['key'] = $code;
                $buffer['status'] = "Deze code is geldig!";
                return;
            } else {
                $buffer['status'] = "Dit is geen geldige code";
                return;
            }
        }
    }
}

function post() {
    global $buffer;
}