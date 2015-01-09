<?php


function manageEmails() {
    global $DB;

    $tsql = "
        SELECT DISTINCT mailbox, gebruikersnaam, 'verkoper' soort
        FROM Gebruiker
          JOIN Voorwerp ON Voorwerp.verkoper = gebruikersnaam
        UNION ALL
        SELECT DISTINCT mailbox, gebruikersnaam, 'koper' soort
        FROM Gebruiker
          JOIN Voowerp ON Voorwerp


    $params = array($voorwerpnummer);

    $stmt =  sqlsrv_query($DB, $tsql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $mailbox = $row['mailbox'];

        sendMail($mailbox, $subject, $body);
    }
}