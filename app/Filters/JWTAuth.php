<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class JWTAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return null;
        }

        $secret = env('JWT_SECRET');
        if (! is_string($secret) || strlen($secret) < 32) {
            return service('response')
                ->setStatusCode(500)
                ->setJSON(['error' => 'JWT_SECRET belum diatur atau kurang dari 32 karakter']);
        }

        $authorization = $request->getHeaderLine('Authorization');
        if (! preg_match('/^Bearer\s+(\S+)$/i', $authorization, $matches)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'token bearer diperlukan']);
        }

        try {
            JWT::decode($matches[1], new Key($secret, 'HS256'));
        } catch (\UnexpectedValueException | \DomainException | \InvalidArgumentException $exception) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'token tidak valid atau sudah kedaluwarsa']);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
