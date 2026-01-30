<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require VENDOR . '/autoload.php';

// Переменные для хранения данных и ошибок
$name = $phone = $email = $message = '';
$errors = [];
$success = false;

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение и очистка данных
    $name = trim($_POST['your-name'] ?? '');
    $phone = trim($_POST['your-phone'] ?? '');
    $email = trim($_POST['your-email'] ?? '');
    $message = trim($_POST['your-message'] ?? '');
    $accept = isset($_POST['accept-this-1']);

    // Валидация
    if (empty($name)) {
        $errors['name'] = 'Имя обязательно для заполнения';
    } elseif (mb_strlen($name, 'UTF-8') < 5 || mb_strlen($name, 'UTF-8') > 50) {
        $errors['name'] = 'Имя должно содержать от 5 до 50 символов';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Телефон обязателен для заполнения';
    } elseif (!preg_match('/^\+7-\d{3}-\d{3}-\d{4}$/', $phone)) {
        $errors['phone'] = 'Некорректный номер телефона';
    }

    if (empty($email)) {
        $errors['email'] = 'Email обязателен для заполнения';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (empty($message)) {
        $errors['message'] = 'Сообщение обязательно для заполнения';
    } elseif (mb_strlen($message, 'UTF-8') < 10 || mb_strlen($message, 'UTF-8') > 255) {
        $errors['message'] = 'Сообщение должно содержать от 10 до 255 символов';
    }

    if (!$accept) {
        $errors['accept'] = 'Необходимо согласие на обработку данных';
    }

    // Если нет ошибок, отправляем письмо
    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // Настройки SMTP для Yandex
            $mail->isSMTP();
            $mail->Host       = 'smtp.yandex.ru';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'advertising-group.kemerovo@yandex.ru';
            $mail->Password   = 'qtytbwcppzyqtwdu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // Отправитель и получатель
            $mail->setFrom('advertising-group.kemerovo@yandex.ru', 'vse42.ru | Аренда');
            $mail->addAddress('yurzin.sergey@mail.ru', 'Ivanov Ivan Ivanovich');
            $mail->addReplyTo($email, $name);

            // Содержимое письма
            $mail->isHTML(true);
            $mail->Subject = 'Новое сообщение с сайта arenda.vse42.ru';

            $mail->Body = "
                <html>
                <head>
                    <meta charset='UTF-8'>
                </head>
                <body>
                    <h2>Новое сообщение с сайта</h2>
                    <table style='border-collapse: collapse; width: 100%; max-width: 600px;'>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;'><strong>Имя:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$name}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;'><strong>Телефон:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$phone}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;'><strong>Email:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'><a href='mailto:{$email}'>{$email}</a></td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5; vertical-align: top;'><strong>Сообщение:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>" . nl2br(htmlspecialchars($message)) . "</td>
                        </tr>
                    </table>
                </body>
                </html>
            ";

            $mail->AltBody = "Имя: {$name}\nТелефон: {$phone}\nEmail: {$email}\nСообщение:\n{$message}";

            $mail->send();
            $success = true;

            // Очищаем поля после успешной отправки
            $name = $phone = $email = $message = '';

        } catch (Exception $e) {
            $errors['general'] = 'Ошибка при отправке сообщения. Попробуйте позже.';
            // Для отладки:
            // $errors['general'] = 'Ошибка: ' . $mail->ErrorInfo;
        }
    }
}