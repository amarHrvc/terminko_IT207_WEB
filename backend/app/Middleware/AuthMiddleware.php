<?php

namespace App\Middleware;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Flight;
use stdClass;

class AuthMiddleware
{
    public function before($params)
    {
        $auth = Flight::request()->header('Auth');
        if (empty($auth)) {
            Flight::jsonHalt(['error' => 'You must be logged in to access this page.'], 403);
        }

        list($headers, $token) = $this->decodeToken($auth);

        $this->isTokenExpired($token);

//        var_dump(User::fromArray((array)$token->user));

        $userModel = User::fromArray((array)$token->user);
        Flight::set('user', $userModel);

//        die(json_encode(Flight::get('user')));
//        var_dump($readableTime);
//        var_dump($headers);
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
