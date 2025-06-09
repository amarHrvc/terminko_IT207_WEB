<?php

namespace App\Helpers;
/**
 * DEVELOPMENT CORS Helper Class
 *
 * This class helps with Cross-Origin Resource Sharing (CORS) for the application.
 * It allows requests from localhost and sets appropriate headers for CORS.
 */
class CORS
{
    public static function handle()
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        fwrite(fopen('php://stderr', 'w'), " ---- $origin \n");

        // Allow any localhost port
        $allowedOrigins = [
            'http://localhost',
            'http://localhost:4200',
            'http://localhost:8080',
            'http://localhost:8100',
            'http://terminko.app',
        ];

        foreach ($allowedOrigins as $allowedOrigin) {
            if (preg_match('/^' . preg_quote($allowedOrigin, '/') . '(:\d+)?$/', $origin)) {
            fwrite(fopen('php://stderr', 'w'), "MATCH ------ $origin \n");

            header("Access-Control-Allow-Origin: $origin");
            break;
            }
        }

        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Auth');
        header('Access-Control-Allow-Credentials: true');

        // Handle OPTIONS preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            fwrite(fopen('php://stderr', 'w'), "MATCH AND EXIT ------ $origin \n");

            exit(0);
        }
    }


    
}
