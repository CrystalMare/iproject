<?php
/**
 * Created by PhpStorm.
 * User: koen
 * Date: 8-1-2015
 * Time: 14:22
 */

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
    $buffer['feedback1']="";
    $buffer['feedback2']="";

}

function get() {
    global $buffer;

    foreach (getFeedbackinfoGekochteVeiling($_SESSION['username']) as $feedback) {

        $buffer['feedback1'] .= <<<"END"
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        <h5>$feedback[feedbacktype]</h5>
                        <p>van:<p> $feedback[verkoper]
                        <br><br>
                        $feedback[commentaar]
                    </div>
                </div>
END;

    }

    foreach (getFeedbackinfoMijnveilingen($_SESSION['username']) as $feedback) {

        $buffer['feedback2'] .= <<<"END"
                    <div class ="col-md-7 nog-te-geven-feedback col-xs-7">
                        <h5>$feedback[feedbacktype]</h5>
                        <p>van:<p>$feedback[verkoper]
                        <br><br>
                        $feedback[commentaar]
                    </div>
                </div>
END;

    }

}

function post() {
    global $buffer;

}

function getFeedbackinfoGekochteVeiling($user) {
    global $DB;
    $tsql = "SELECT Voorwerp.verkoper, Feedback.feedbacktype, Feedback.commentaar
             FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
             WHERE Voorwerp.koper=(?) AND Feedback.gebruikersoort='verkoper'";
    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $feedback = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($feedback, $row);
    }
    return $feedback;
}

function getFeedbackinfoMijnveilingen($user) {
    global $DB;
    $tsql = "SELECT Voorwerp.verkoper, Feedback.feedbacktype, Feedback.commentaar
             FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
             WHERE Voorwerp.verkoper=(?) AND Feedback.gebruikersoort='koper'";
    $params = array($user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $feedback = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($feedback, $row);
    }
    return $feedback;
}


