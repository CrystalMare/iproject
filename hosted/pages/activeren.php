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

    if (isset($_GET['status']) && $_GET['status'] == 'sent') {
        $buffer['status'] = "Er is een email naar u verstuurd met de verificatiecode.";
        return;
    }


    if (!isset($_GET['key']) || !isset($_GET['date']) || !isset($_GET['email'])) {
        $buffer['status'] = "Dit is geen volledige code";
        return;
    } else {
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
                header("Location: index.php?page=registreeraccount&key=$code");
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
    global $config;
    if (!isset($_POST['registreer-code'])) {
        $buffer['stats'] = "Voer een geldige code in.";
        return;
    }
    $code = explode(str_replace('$', '@',$_POST['registreer-code']), ':');
    //Order: hash, email, date

    if (intval($code[2]) > (time() + (60*60*4))) {
        $buffer['status'] = "Deze code is verlopen en niet meer geldig.";
        return;
    }
    else {
        if (checkKey($code[0], $code['1'], $code['2'])) {
            $_SESSION['register']['email'] = $code[1];
            $_SESSION['register']['date'] = $code[2];
            $_SESSION['register']['key'] = $code[0];
            $buffer['status'] = "Deze code is geldig!";
            header("Location: index.php?page=registreeraccount&key=$code");
            return;
        } else {
            $buffer['status'] = "Dit is geen geldige code";
            return;
        }
    }
}