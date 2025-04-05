<?php

namespace Tests\Db;

use PDO;

class DatabaseHelper
{
    private static ?PDO $instance = null;


    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=terminko;charset=utf8mb4',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (\PDOException $e) {
                fwrite(STDERR, "❌ Failed to connect to DB: " . $e->getMessage() . "\n");
                exit(1); // Stop running tests
            }
        }

        return self::$instance;
    }


    public static function setupTestDatabase(): void
    {
        $db = self::getInstance();

        // Drop and recreate test tables
        $db->exec('DROP TABLE IF EXISTS users');
        $db->exec('
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL
            )
        ');
    }


}
