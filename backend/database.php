<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Database
{
    private static $instances = [];

    public static function getConnection($dbName = null)
    {
        if (!$dbName) {
            $dbName = $_ENV['DB_NAME'];
        }

        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'] ?? 3306;

        if (!isset(self::$instances[$dbName])) {
            try {
                $pdo = new PDO("mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname=$dbName;charset=utf8", $_ENV['DB_USER'], $_ENV['DB_PASS']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instances[$dbName] = $pdo;
            } catch (PDOException $e) {
              throw new Exception("Erreur de connexion : " . $e->getMessage());
            }
        }

        return self::$instances[$dbName];
    }
}
