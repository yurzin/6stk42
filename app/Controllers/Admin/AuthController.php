<?php

namespace App\Controllers\Admin;

use App\Models\User;
use core\Request;
use core\Response;

class AuthController
{
    public function login(Request $request): never
    {
        $data  = $request->json();
        $email = strtolower(trim($data['email'] ?? ''));
        $pass  = $data['password'] ?? '';

        // Валидация
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email обязателен для заполнения';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный формат email';
        }
        if (empty($pass)) {
            $errors['password'] = 'Пароль обязателен для заполнения';
        }

        if (!empty($errors)) {
            Response::json(['errors' => $errors], 422);
        }

        // Ищем пользователя
        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        if (!$user || !password_verify($pass, $user['password'])) {
            Response::json(
                ['errors' => ['email' => 'Неверный email или пароль']],
                401
            );
        }

        $token = $this->generateToken($user);

        Response::json([
            'message' => 'Авторизация успешна',
            'token'   => $token,
            'user'    => [
                'id'    => $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ]);
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