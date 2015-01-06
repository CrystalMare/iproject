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
    $buffer['looptijden'] = "";
    $buffer['countries'] = "";
}

function get() {
    global $buffer;
    if (!isset($_SESSION['category1'])) {
        header('Location: index.php?page=productveilgen');
        exit();
    }

    //Looptijd
    foreach (DatabaseTools::getAuctionDurations() as $value) {
        $buffer['looptijden'] .= "<option value='$value'>$value</option>";
    }
    unset($value);
    //Landen
    foreach (DatabaseTools::getCountries() as $value) {
        if ($value == "Netherlands") {
            $buffer['countries'] .= "<option selected='selected' value='$value'>$value</option>";
        } else {
            $buffer['countries'] .= "<option value='$value'>$value</option>";
        }
    }


}

function post() {
    global $buffer;
    if (($high1 = getHigh1()) == null) {
        header('Location: index.php?page=productveilen');
        exit();
    }
    if ($_POST["categorieaantal"] == 2) {
        $_SESSION['category2'] = getHigh2();
    }


    $_SESSION['category1'] = $high1;
    header('Location: index.php?page=productplaatsen');
    exit();
}

function getHigh1()
{
    if (isset($_POST['category1'])) {
        if (isset($_POST['category1sub1'])) {
            if (isset($_POST['category1sub2'])) {
                if (isset($_POST['category1sub3'])) {
                    if (isset($_POST['category1sub4'])) {
                        return  $_POST['category1sub4'];
                    }
                    return $_POST['category1sub3'];
                }
                return $_POST['category1sub2'];
            }
            return $_POST['category1sub1'];
        }
        return $_POST['category1'];
    }
    return null;
}

function getHigh2()
{
    if (isset($_POST['category2'])) {
        if (isset($_POST['category2sub1'])) {
            if (isset($_POST['category2sub2'])) {
                if (isset($_POST['category2sub3'])) {
                    if (isset($_POST['category2sub4'])) {
                        return  $_POST['category2sub4'];
                    }
                    return $_POST['category2sub3'];
                }
                return $_POST['category2sub2'];
            }
            return $_POST['category2sub1'];
        }
        return $_POST['category2'];
    }
    return null;
}