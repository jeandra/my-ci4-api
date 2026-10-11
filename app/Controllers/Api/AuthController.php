<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    protected $format = 'json';

    public function register()
    {
        $this->setCorsHeaders();

        $data = $this->request->getJSON(true);
        if (! is_array($data)) {
            return $this->failValidationErrors('request body harus berupa JSON yang valid');
        }

        if (! is_string($data['username'] ?? null) || ! is_string($data['email'] ?? null) || ! is_string($data['password'] ?? null)) {
            return $this->failValidationErrors('username, email, dan password harus berupa teks');
        }

        $data['username'] = trim($data['username']);
        $data['email']    = strtolower(trim($data['email']));

        $validation = service('validation');
        $validation->setRules([
            'username' => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|max_length[254]',
            'password' => 'required|min_length[8]|max_length[72]',
        ]);

        if (! $validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $username = $data['username'];
        $email    = $data['email'];
        $password = $data['password'];

        if (strlen($password) > 72) {
            return $this->failValidationErrors(['password' => 'password maksimal 72 byte']);
        }

        $users = new UserModel();
        if ($users->where('username', $username)->orWhere('email', $email)->first() !== null) {
            return $this->failResourceExists('username atau email sudah terdaftar');
        }

        $secret = $this->jwtSecret();
        if ($secret === null) {
            return $this->failServerError('JWT_SECRET belum diatur atau kurang dari 32 karakter');
        }

        $userId = $users->insert([
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ], true);

        if ($userId === false) {
            return $this->failServerError('gagal membuat akun');
        }

        return $this->respondCreated([
            'message' => 'registrasi berhasil',
            'data'    => $this->userWithToken($userId, $username, $email, $secret),
        ]);
    }

    public function login()
    {
        $this->setCorsHeaders();

        $data = $this->request->getJSON(true);
        if (! is_array($data)) {
            return $this->failValidationErrors('request body harus berupa JSON yang valid');
        }

        if (! is_string($data['email'] ?? null) || ! is_string($data['password'] ?? null)) {
            return $this->failValidationErrors('email dan password harus berupa teks');
        }

        $data['email'] = strtolower(trim($data['email']));

        $validation = service('validation');
        $validation->setRules([
            'email'    => 'required|valid_email|max_length[254]',
            'password' => 'required',
        ]);

        if (! $validation->run($data)) {
            return $this->failValidationErrors($validation->getErrors());
        }

        $secret = $this->jwtSecret();
        if ($secret === null) {
            return $this->failServerError('JWT_SECRET belum diatur atau kurang dari 32 karakter');
        }

        $email = $data['email'];
        $user  = (new UserModel())->where('email', $email)->first();

        if ($user === null || ! password_verify($data['password'], $user['password'])) {
            return $this->failUnauthorized('email atau password tidak valid');
        }

        return $this->respond([
            'message' => 'login berhasil',
            'data'    => $this->userWithToken($user['id'], $user['username'], $user['email'], $secret),
        ]);
    }

    public function options()
    {
        $this->setCorsHeaders();
        return $this->response->setStatusCode(200);
    }

    private function setCorsHeaders(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    }

    private function jwtSecret(): ?string
    {
        $secret = env('JWT_SECRET');
        return is_string($secret) && strlen($secret) >= 32 ? $secret : null;
    }

    private function userWithToken(int|string $userId, string $username, string $email, string $secret): array
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + 3600;
        $token = JWT::encode([
            'sub'  => (string) $userId,
            'iat'  => $issuedAt,
            'exp'  => $expiresAt,
        ], $secret, 'HS256');

        return [
            'user' => [
                'id'       => $userId,
                'username' => $username,
                'email'    => $email,
            ],
            'token'      => $token,
            'token_type' => 'Bearer',
            'expires_at' => date(DATE_ATOM, $expiresAt),
        ];
    }
}
