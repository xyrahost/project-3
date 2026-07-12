<?php

require_once __DIR__ . "/mailer.php";
require_once __DIR__ . "/../config/config.php";

function sendResetPasswordEmail($email, $full_name, $reset_token)
{
    try {

        $mail = getMailer();

        $mail->addAddress($email, $full_name);

        $reset_link = BASE_URL . "/reset-password.php?token=" . urlencode($reset_token);

        $mail->Subject = "Reset Password " . SITE_NAME;

        $mail->Body = "
        <h2>Halo, {$full_name}</h2>

        <p>Kami menerima permintaan reset password.</p>

        <p>
        <a href='{$reset_link}'>
        Reset Password
        </a>
        </p>

        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        ";

        $mail->AltBody = $reset_link;

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }
}