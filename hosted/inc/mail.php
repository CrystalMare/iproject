<?php
include(inc . 'PHPMailerAutoload.php');

function sendCode($email) {
    GLOBAL $CONFIG, $DB;
    //Check username
    $sql = "SELECT mailbox "
        . "FROM Gebruiker "
        . "WHERE mailbox = '?';";
    $stmt = sqlsrv_query($DB, $sql, array($email));
    if (!$stmt) return "email_failure";
    if (sqlsrv_has_rows($stmt)) return "database_error";

    $date = time();
    $hash = hash("sha256", $email . $date);
    $code = "email=$email&date=$date&key=$hash";

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = $CONFIG['mail']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $CONFIG['mail']['username'];
    $mail->Password = $CONFIG['mail']['password'];
    $mail->Port = $CONFIG['mail']['port'];
    if ($CONFIG['mail']['tls']) $mail->SMTPSecure = 'tls';

    $mail->From = $CONFIG['mail']['from'];
    $mail->FromName = $CONFIG['mail']['fullname'];

    $mail->addAddress($email);
    $host = $CONFIG['site']['host'];
    $from = $CONFIG['mail']['fullname'];
    $mail->Subject = "Activatiecode -  EenmaalAndermaal";
    $mail->Body = "Geachte Heer/Mevrouw,<br /><br />"
        . "Welkom bij EenmaalAndermaal!<br />"
        . "Hierbij versturen wij u de bevestigingscode voor uw registratie.<br />"
        . "Om de code te activeren klikt u op de onderstaande link:<br /><br />"
        . "<a href='http://$host?page=activeren&$code'>http://$host?p=activeren&$code</a><br /><br />"
        . "Met vriendelijke groet,<br /><br />"
        . "$from";
    $mail->isHTML();

    return $mail->Send();
}

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
    $host = $CONFIG['site']['host'];
    $from = $CONFIG['mail']['fullname'];
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->isHTML();
    $mail->send();
}