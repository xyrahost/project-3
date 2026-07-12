<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

function getMailer()
{
    $mail = new PHPMailer(true);

    // Konfigurasi SMTP
    $mail->isSMTP();
    $mail->Host       = 'mail.techweb.biz.id';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'noreply@techweb.biz.id';
    $mail->Password   = '@Xyra9630';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Pengaturan Email
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    // Pengirim
    $mail->setFrom(
        'noreply@techweb.biz.id',
        'TechWeb'
    );

    return $mail;
}
