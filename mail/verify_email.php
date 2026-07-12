<?php

require_once __DIR__ . "/mailer.php";
require_once __DIR__ . "/../config/config.php";

function sendVerificationEmail($email, $full_name, $verify_token)
{
    try {

        $mail = getMailer();

        $mail->addAddress($email, $full_name);

        $verify_link = BASE_URL . "/verify.php?token=" . urlencode($verify_token);

        $mail->Subject = "Verifikasi Akun " . SITE_NAME;

        $mail->Body = "
        <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:20px;border:1px solid #ddd;border-radius:8px;'>

            <h2>Halo, {$full_name} 👋</h2>

            <p>Terima kasih telah mendaftar di <b>" . SITE_NAME . "</b>.</p>

            <p>Silakan klik tombol di bawah untuk memverifikasi akun Anda.</p>

            <p style='text-align:center;margin:30px 0;'>
                <a href='{$verify_link}'
                style='background:#0d6efd;
                color:#fff;
                text-decoration:none;
                padding:12px 25px;
                border-radius:6px;
                display:inline-block;'>
                Verifikasi Email
                </a>
            </p>

            <p>Jika tombol tidak berfungsi, salin link berikut ke browser:</p>

            <p>{$verify_link}</p>

            <hr>

            <small>Email ini dikirim otomatis oleh " . SITE_NAME . ". Mohon jangan membalas email ini.</small>

        </div>
        ";

        $mail->AltBody = "Verifikasi akun Anda melalui link berikut:\n\n{$verify_link}";

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }
}