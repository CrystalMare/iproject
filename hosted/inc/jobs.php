<?php




function manageEmails($voorwerpnummer) {
    require inc . 'mail.php';
    global $DB;

    $mailadress = "";
    $subject = "";
    $body = "";
    $tsql = "
        SELECT mailbox
        FROM Voorwerp JOIN Gebruiker
        ON Voorwerp.verkoper = Gebruiker.gebruikersnaam
        WHERE Gebruiker.gebruikersnaam = Voorwerp.verkoper
        AND voorwerpnummer = ?";


    $params = array($voorwerpnummer);

    $stmt =  sqlsrv_query($DB, $tsql, $params);
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $mailbox = $row['mailbox'];

        sendMail($mailbox, $subject, $body);
    }
}