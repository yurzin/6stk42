<?php

namespace App\Controllers\Admin;

use App\Models\User;
use core\Request;
use core\Response;

class RegisteredUserController
{
    /**
     * Возвращает форму регистрации (метаданные для SPA)
     */
    public function create(): never
    {
        Response::json([
            'fields' => ['name', 'email', 'password'],
            'rules'  => [
                'name'     => 'min:5|max:50',
                'email'    => 'email',
                'password' => 'min:8',
            ],
        ]);
    }

    /**
     * Регистрирует нового пользователя
     */
    public function store(Request $request): never
    {
        $data   = $request->json();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            Response::json(['errors' => $errors], 422);
        }

        $userModel = new User();

        if ($userModel->findByEmail($data['email'])) {
            Response::json(
                ['errors' => ['email' => 'Этот email уже зарегистрирован']],
                422
            );
        }

        $user = $userModel->create([
            'name'     => htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8'),
            'email'    => strtolower(trim($data['email'])),
            'password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'role'     => 'user',
        ]);

        $token = $this->generateToken($user);

        Response::json([
            'message' => 'Пользователь успешно зарегистрирован',
            'token'   => $token,
            'user' => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ], 201);
    }

    // ─── Приватные вспомогательные методы ────────────────────────────────

    private function validate(array $data): array
    {
        $errors = [];
        $name   = $data['name']  ?? '';
        $email  = $data['email'] ?? '';
        $pass   = $data['password'] ?? '';

        // name
        if (empty($name)) {
            $errors['name'] = 'Имя обязательно для заполнения';
        } elseif (mb_strlen($name, 'UTF-8') < 5 || mb_strlen($name, 'UTF-8') > 50) {
            $errors['name'] = 'Имя должно содержать от 5 до 50 символов';
        } elseif (preg_match('/[\r\n<>]/', $name)) {
            $errors['name'] = 'Имя содержит недопустимые символы';
        }

        // email
        if (empty($email)) {
            $errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный формат email';
        } elseif (preg_match('/[\r\n]/', $email)) {
            $errors['email'] = 'Некорректный email';
        }

        // password
        if (empty($pass)) {
            $errors['password'] = 'Пароль обязателен для заполнения';
        } elseif (mb_strlen($pass, 'UTF-8') < 8) {
            $errors['password'] = 'Пароль должен содержать минимум 8 символов';
        }

        return $errors;
    }

    private function generateToken(array $user): string
    {
        $header  = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'sub'  => $user['id'],
            'role' => $user['role'],
            'iat'  => time(),
            'exp'  => time() + 3600 * 24,
        ]));
        $sig = hash_hmac('sha256', "$header.$payload", $_ENV['JWT_SECRET']);
        return "$header.$payload.$sig";
    }
}