<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require VENDOR . '/autoload.php';

// Генерация CSRF-токена если его нет
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Переменные для хранения данных и ошибок
$name = $phone = $email = $message = '';
$errors = [];
$success = false;

// Обработка формы при отправке
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка CSRF-токена
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors['general'] = 'Ошибка безопасности. Обновите страницу и попробуйте снова.';
    } else {
        // Получение и очистка данных
        $name = trim($_POST['your-name'] ?? '');
        $phone = trim($_POST['your-phone'] ?? '');
        $email = trim($_POST['your-email'] ?? '');
        $message = trim($_POST['your-message'] ?? '');
        $accept = isset($_POST['your-accept']);

        // Валидация
        if (empty($name)) {
            $errors['name'] = 'Имя обязательно для заполнения';
        } elseif (mb_strlen($name, 'UTF-8') < 5 || mb_strlen($name, 'UTF-8') > 50) {
            $errors['name'] = 'Имя должно содержать от 5 до 50 символов';
        }

        // Валидация телефона
        if (empty($phone)) {
            $errors['phone'] = 'Телефон обязателен для заполнения';
        } else {
            // Убираем все нецифровые символы кроме +
            $cleanPhone = preg_replace('/[^\d+]/', '', $phone);
            if (!preg_match('/^\+7\d{10}$/', $cleanPhone) && !preg_match('/^8\d{10}$/', $cleanPhone)) {
                $errors['phone'] = 'Некорректный номер телефона. Пример: +7-912-345-67-89';
            }
        }

        if (empty($email)) {
            $errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        }

        if (empty($message)) {
            $errors['message'] = 'Сообщение обязательно для заполнения';
        } elseif (mb_strlen($message, 'UTF-8') < 50 || mb_strlen($message, 'UTF-8') > 255) {
            $errors['message'] = 'Сообщение должно содержать от 50 до 255 символов';
        }

        if (!$accept) {
            $errors['accept'] = 'Необходимо согласие на обработку данных';
        }

        // Если нет ошибок, отправляем письмо
        if (empty($errors)) {
            $mail = new PHPMailer(true);

            try {
                // Настройки SMTP
                $mail->isSMTP();
                $mail->Host       = getenv('SMTP_HOST');
                $mail->SMTPAuth   = true;
                $mail->Username   = getenv('SMTP_USERNAME');
                $mail->Password   = getenv('SMTP_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = getenv('SMTP_PORT');
                $mail->CharSet    = 'UTF-8';

                // Отправитель и получатель
                $mail->setFrom(getenv('MAIL_FROM'), 'vse42.ru | Аренда');
                $mail->addAddress(getenv('MAIL_TO'), 'Получатель');
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
                                <td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($name) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;'><strong>Телефон:</strong></td>
                                <td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($phone) . "</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px; border: 1px solid #ddd; background-color: #f5f5f5;'><strong>Email:</strong></td>
                                <td style='padding: 8px; border: 1px solid #ddd;'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></td>
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

                if ($mail->send()) {
                    $success = true;
                    // Очищаем поля после успешной отправки
                    $name = $phone = $email = $message = '';
                    // Регенерируем CSRF-токен для предотвращения повторной отправки
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }

            } catch (Exception $e) {
                // Для отладки - показываем реальную ошибку
                $errors['general'] = 'Ошибка при отправке сообщения: ' . $e->getMessage();
                // Или логируем
                error_log('Mailer Error: ' . $e->getMessage());
                error_log('SMTP Debug: ' . $mail->ErrorInfo);
            }
        }
    }
}