<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require ROOT . '/vendor/autoload.php';
require CONFIG . '/rate-limiter.php';

// Инициализация Rate Limiter
$rateLimiter = new RateLimiter(
    maxAttempts: 3,      // 3 попытки
    decayMinutes: 15     // за 15 минут
);

$limiterKey = RateLimiter::key('contact-form');

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

    // 1. ПРОВЕРКА HONEYPOT (ловушка для ботов)
    if (!empty($_POST['website'])) {
        error_log('Honeypot triggered for IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        // Делаем вид что всё ОК, но ничего не отправляем
        $success = true;
        $name = $phone = $email = $message = '';
        usleep(random_int(100000, 300000));
        // Останавливаем дальнейшую обработку
    }
    // 2. ПРОВЕРКА RATE LIMIT
    elseif ($rateLimiter->tooManyAttempts($limiterKey)) {
        $waitTime = ceil($rateLimiter->availableIn($limiterKey) / 60);
        $errors['general'] = "Слишком много попыток отправки. Попробуйте через {$waitTime} мин.";
        error_log("Rate limit exceeded for IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        usleep(random_int(100000, 300000));
    }
    // 3. CSRF проверка
    elseif (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $rateLimiter->hit($limiterKey);
        $errors['general'] = 'Ошибка безопасности. Обновите страницу и попробуйте снова.';
        error_log('CSRF attack attempt from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        usleep(random_int(100000, 300000));
    }
    // 4. Валидация и отправка
    else {
        // Получение и очистка данных
        $name = trim($_POST['your-name'] ?? '');
        $phone = trim($_POST['your-phone'] ?? '');
        $email = trim($_POST['your-email'] ?? '');
        $message = trim($_POST['your-message'] ?? '');
        $accept = isset($_POST['your-accept']);

        // Валидация имени
        if (empty($name)) {
            $errors['name'] = 'Имя обязательно для заполнения';
        } elseif (mb_strlen($name, 'UTF-8') < 5 || mb_strlen($name, 'UTF-8') > 50) {
            $errors['name'] = 'Имя должно содержать от 5 до 50 символов';
        }

        // Валидация телефона
        if (empty($phone)) {
            $errors['phone'] = 'Телефон обязателен для заполнения';
        } else {
            $cleanPhone = preg_replace('/[^\d]/', '', $phone);
            if (strlen($cleanPhone) !== 11 || ($cleanPhone[0] !== '7' && $cleanPhone[0] !== '8')) {
                $errors['phone'] = 'Некорректный номер телефона';
            }
        }

        // Валидация email
        if (empty($email)) {
            $errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        }

        // Валидация сообщения
        if (empty($message)) {
            $errors['message'] = 'Сообщение обязательно для заполнения';
        } elseif (mb_strlen($message, 'UTF-8') < 50 || mb_strlen($message, 'UTF-8') > 255) {
            $errors['message'] = 'Сообщение должно содержать от 50 до 255 символов';
        }

        // Проверка согласия
        if (!$accept) {
            $errors['accept'] = 'Необходимо согласие на обработку данных';
        }

        // Если есть ошибки валидации
        if (!empty($errors)) {
            $rateLimiter->hit($limiterKey);
            usleep(random_int(100000, 300000));
        }
        // Если всё ОК - отправляем
        else {
            $mail = new PHPMailer(true);

            try {
                // Настройки SMTP
                $mail->isSMTP();
                $mail->Host       = $_ENV['SMTP_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $_ENV['SMTP_USERNAME'];
                $mail->Password   = $_ENV['SMTP_PASSWORD'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = $_ENV['SMTP_PORT'];
                $mail->CharSet    = 'UTF-8';

                // Отправитель и получатель
                $mail->setFrom($_ENV['MAIL_FROM'], 'vse42.ru | Аренда');
                $mail->addAddress($_ENV['MAIL_TO'], 'Получатель');
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
                    // Сбрасываем счётчик после успешной отправки
                    $rateLimiter->clear($limiterKey);
                }

            } catch (Exception $e) {
                error_log('Mailer Error: ' . $e->getMessage());
                error_log('SMTP Debug: ' . $mail->ErrorInfo);
                $errors['general'] = 'Произошла ошибка при отправке. Попробуйте позже.';
                // Считаем неудачную попытку
                $rateLimiter->hit($limiterKey);
            }

            // Задержка ВСЕГДА (успех или неудача)
            usleep(random_int(100000, 300000));
        }
    }
}

// Периодическая очистка устаревших файлов (1% вероятность)
if (random_int(1, 100) === 1) {
    $rateLimiter->cleanup();
}