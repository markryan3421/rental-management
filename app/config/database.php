<?php
declare(strict_types=1);
class Database
{
    private static ?PDO $connection = null;
    public static function getConnection() : PDO
    {
        if(self::$connection === null)
        {
            $databaseConnectionString = "mysql:host=localhost;dbname=mini_capstone_db;charset=utf8mb4";
            $db_username = "root";
            $db_password = "";

            self::$connection = new PDO($databaseConnectionString, $db_username, $db_password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]); // PDO (php data object)
        }
        return self::$connection;
    }
}

    