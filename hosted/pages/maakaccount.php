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
    header("Location: ?page=registreren");
    exit();
}

function post() {
    global $buffer;
    $state = verifyRegistration();
    if ($state == 'ok') {
        $state = commitRegistration();
    } else {

        $key = $_SESSION['register']['key'];
        header("Location: ?page=registreeraccount&key=$key&err=$state");
        exit();
    }


    if ($state != 'ok') {
        $key = $_SESSION['register']['key'];
        header("Location: ?page=registreeraccount&key=$key&err=$state");
        exit();
    }

    for($count = 0; $count < ImageProvider::getImagesForAuction($iteminfo['voorwerpnummer'])->getImageCount(); $count++) {
        $col = $count==0 ? 12 : 4;
        $buffer['pic'] .= <<<"END"
    <div class="col-md-$col kleine-thumbnail col-xs-$col">
                    <a href="#" data-toggle="modal" data-target="#basicModal$count">
                        <img src="inc/image.php?auction=$auction&id=$count" alt="geen foto" class="img-thumbnail">
                    </a>                </div>

                <div class="modal fade" id="basicModal$count" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                                <h5 class="modal-title" id="myModalLabel">$iteminfo[titel]</h5>
                            </div>
                            <div class="modal-body">
                                <img src="inc/image.php?auction=$auction&id=$count" alt="geen foto" class="img-thumbnail">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Terug</button>
                            </div>
                        </div>
                    </div>
                </div>
END;

    }

}
function verifyRegistration() {
    global $DB;
    if (!isset($_POST['username'])) return "field_missing";
    $user = $_POST['username'];
    //Check username
    $sql = "SELECT gebruikersnaam FROM Gebruiker WHERE gebruikersnaam = '?';";
    $stmt = sqlsrv_query($DB, $sql, array($user));
    if (!$stmt) return "user_exsits";
    if (sqlsrv_has_rows($stmt)) return "user_exists";

    //Check pw
    if (strlen(($_POST['password'])) < 6) {
        return "pw_short";
    } elseif ($_POST['password'] != $_POST['repeatpassword']) {
        return "pw_notsame";
    }

    if ($_SESSION['register']['key'] != $_GET['key']) return "key_error";
    return 'ok';
}

function commitRegistration() {
    global $DB;
    $sql =    "INSERT INTO Gebruiker "
        . "(gebruikersnaam, voornaam, achternaam, adresregel1, adresregel2, "
        . "postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, vraag, antwoordtekst, salt) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $salt = randomSalt();
    $wachtwoord = hash('sha256', $_POST['password'] . $salt);
    $antwoord = hash('sha256', $_POST['questionAnswer']);
    $geboortedag = $_POST['year'] . $_POST['month'] . $_POST['day'];
    $params = array(
        $_POST['username'], $_POST['firstname'], $_POST['lastname'], $_POST['adres'], $_POST['adres2'],
        str_replace(" ", "", $_POST['zipcode']), $_POST['town'], $_POST['country'], $geboortedag,
        $_SESSION['register']['email'], $wachtwoord, $_POST['vraag'], $antwoord, $salt
    );
    var_dump($params);
    $stmt = sqlsrv_query($DB, $sql, $params);
    if (!sqlsrv_errors()) {
        return "ok";
    } else {
        return "check";
    }
}

function randomSalt() {
    $rawsalt = uniqid();
    $salt = substr($rawsalt, 0, 8);
    return $salt;
}