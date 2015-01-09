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

}

function get() {
    global $buffer;
    var_dump(FeedbackVerkoper($_SESSION['username']));
    var_dump(FeedbackKoper($_SESSION['username']));

}

function post() {
    global $buffer;

}

function getFeedbackinfo($auction) {
    global $DB;
    $tsql = "SELECT commentaar, datumtijd, feedbacktype, gebruikersoort, voorwerpnummer FROM Feedback WHERE voorwerpnummer = ?;";
    $params = array($auction);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

function FeedbackVerkoper($user){
    global $DB;
    $tsql = "SELECT Voorwerp.voorwerpnummer
    FROM Voorwerp
    WHERE Voorwerp.koper=?
    AND NOT EXISTS (SELECT *
                    FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
                    WHERE Voorwerp.koper=? AND Feedback.gebruikersoort='verkoper')";
    $params = array($user, $user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $feedback = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($feedback, $row);
    }
    return $feedback;
}

function FeedbackKoper($user){
    global $DB;
    $tsql = "SELECT Voorwerp.voorwerpnummer
    FROM Voorwerp
    WHERE Voorwerp.verkoper=? AND Voorwerp.gesloten=1
    AND EXISTS (SELECT Voorwerp.voorwerpnummer
                      FROM Voorwerp INNER JOIN Feedback ON Voorwerp.voorwerpnummer = Feedback.voorwerpnummer
                      WHERE Voorwerp.verkoper=? AND Feedback.gebruikersoort='koper')";
    $params = array($user, $user);
    $stmt = sqlsrv_query($DB, $tsql, $params);
    $feedback = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        array_push($feedback, $row);
    }
    return $feedback;
}