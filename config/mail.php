<?php
// config/mail.php

return [
    'smtp_host' => 'smtp.mail.ru',
    'smtp_port' => 465,
    'smtp_secure' => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS,
    'smtp_username' => 'your-email@mail.ru',
    'smtp_password' => 'your-password',
    'from_email' => 'your-email@mail.ru',
    'from_name' => 'Сайт Кузбасская 33А',
    'to_email' => 'yurzin.sergey@mail.ru',
    'to_name' => 'Сергей'
];