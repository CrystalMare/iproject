<?php

require '../config.php';
require('PHPMailerAutoload.php');
openDB();
manageEmails();
closeDB();
echo('Done.');
exit();

function sendMail($email, $subject, $body) {
    GLOBAL $CONFIG;
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $CONFIG['mail']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $CONFIG['mail']['username'];
    $mail->Password = $CONFIG['mail']['password'];
    $mail->Port = $CONFIG['mail']['port'];
    if ($CONFIG['mail']['tls']) $mail->SMTPSecure = 'tls';

    $mail->From = $CONFIG['mail']['from'];
    $mail->FromName = $CONFIG['mail']['fullname'];

    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML();
    return $mail->send();
}

function manageEmails() {
    global $DB;

    $tsql = "
        SELECT DISTINCT mailbox, titel, (SELECT mailbox FROM Gebruiker WHERE gebruikersnaam = Voorwerp.koper) anderepartij,
        gebruikersnaam, verkoopprijs, koper, Voorwerp.verkoper, voorwerpnummer, 'verkoper' soort, CAST(
            CASE WHEN (koper IS NULL) THEN 0 ELSE 1 END AS BIT) verkocht
        FROM Gebruiker
          JOIN Voorwerp ON Voorwerp.verkoper = gebruikersnaam
        WHERE Voorwerp.email = 0 AND gesloten = 1
        UNION ALL
        SELECT DISTINCT mailbox, titel, (SELECT mailbox FROM Gebruiker WHERE gebruikersnaam = Voorwerp.verkoper) anderepartij,
        gebruikersnaam, verkoopprijs, koper, Voorwerp.verkoper, voorwerpnummer, 'koper' soort, CAST(1 AS BIT) verkocht
        FROM Gebruiker
          JOIN Voorwerp ON Voorwerp.koper = gebruikersnaam
        WHERE Voorwerp.email = 0 AND gesloten = 1;";

    $stmt =  sqlsrv_query($DB, $tsql);

    $ids = array();

    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if ($row['soort'] == 'verkoper') {
            sendMail($row['mailbox'], 'Veiling #' . $row['voorwerpnummer'] . ' is voltooid.', getMailForSeller($row));
        } else if ($row['soort'] == 'koper') {
            sendMail($row['mailbox'], 'Veiling #' . $row['voorwerpnummer'] . ' is voltooid.', getMailForBuyer($row));
        }
        if (!in_array($row['voorwerpnummer'], $ids))
            array_push($ids, $row['voorwerpnummer']);
    }

    $tsql = "UPDATE Voorwerp SET email = 1 WHERE voorwerpnummer = ?;";
    $voorwerpnummer = 0;
    $ps = sqlsrv_prepare($DB, $tsql, array(&$voorwerpnummer));
    var_dump(sqlsrv_errors());
    foreach ($ids as $key) {
        $voorwerpnummer = $key;
        sqlsrv_execute($ps);
    }
}

function getMailForSeller($mail) {
    global $CONFIG;
    $fulllink = "http://" . $CONFIG['site']['host'] . "?page=product&veiling=$mail[voorwerpnummer]";
    $veiling = "<a href='$fulllink'>$mail[titel]</a>";
    $body = "Beste $mail[gebruikersnaam], <br />";
    $body .= "Uw veiling $veiling is voltooid en ";
    $body .=($mail['verkocht'] ? "is verkocht aan $mail[koper] voor $mail[verkoopprijs] euro." : "niet verkocht.");
    $body .= "<br />";
    $body .= $mail['verkocht'] ? "U kunt de koper bereiken door met $mail[anderepartij] te mailen.<br />" : "";
    $body .= "Met vriendelijke groeten, Eenmaal Andermaal.";
    return $body;
}

function getMailForBuyer($mail) {
    global $CONFIG;
    $fulllink = "http://" . $CONFIG['site']['host'] . "?page=product&veiling=$mail[voorwerpnummer]";
    $veiling = "<a href='$fulllink'>$mail[titel]</a>";
    $body = "Beste $mail[gebruikersnaam], <br />";
    $body .= "Gefeliciteerd u heeft het winnende bod van $mail[verkoopprijs] op de veiling: <br />$veiling<br />";
    $body .= "De verkoper zal contact met u opnemen via uw emailadres: $mail[mailbox]<br />";
    $body .= "Mocht dit niet gebeuren, dan kunt u zelf contact opzoeken via het emailadres: $mail[anderepartij]<br />";
    $body .= "Met vriendlijke groeten, Eenmaal Andermaal.";
    return $body;
}