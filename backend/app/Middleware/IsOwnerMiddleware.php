<?php

namespace App\Middleware;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;
use stdClass;

class IsOwnerMiddleware
{
    public function before($params)
    {
        $userModel = Flight::get('user');

        if ($userModel->isAdmin()) {
            return true;
        }

        if (!$userModel->isOwner()) {
            Flight::jsonHalt([
                'error' => 'user do not have sufficient rights to perform this action (OwnerID)!',
                'user' => $userModel->toSlimArray()], 403);
        }

        return $userModel->isOwner() || $userModel->isAdmin();
    }

    public function after($params)
    {
//        echo 'Middleware last!';
    }

    /**
     * @param string $auth
     * @return array
     */
    public function decodeToken(string $auth): array
    {
        $headers = new stdClass();
        $token = JWT::decode($auth, new Key($_ENV['JWT_SECRET'], 'HS256'), $headers);
        return array($headers, $token);
    }

    /**
     * @param mixed $token
     * @return void
     */
    public function isTokenExpired(mixed $token): void
    {
        $expTime = $token->exp;

        // Convert to a human-readable format (optional)
        $readableTime = date('Y-m-d H:i:s', $expTime);

        if ($expTime < time()) {
            Flight::jsonHalt(['error' => 'Token expired'], 403);
        }
    }
}
