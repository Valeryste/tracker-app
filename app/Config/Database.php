<?php

declare(strict_types = 1);

namespace App\Config;

use Exception;
use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    /**
     * @throws Exception
     */
    public static function getConnection() : PDO|null
    {
        if(is_null(self::$connection)) {
            try {
                $db_host = getenv('DB_HOST') ?: 'db';
                $db_name = getenv('MYSQL_DATABASE') ?: 'tracker_app';

                self::$connection = new PDO(
                    "mysql:host={$db_host};dbname={$db_name}",
                    getenv('MYSQL_USER') ?: 'user',
                    getenv('MYSQL_PASSWORD') ?: 'password'
                );

            } catch (PDOException $e) {
                throw new Exception("Database connection error: " . $e->getMessage());
            }

        }

        return self::$connection;
    }


}