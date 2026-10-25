<?php
declare(strict_types=1);

/**
 * Returns a shared PDO instance configured for MySQL.
 *
 * @return PDO
 * @throws PDOException
 */
function getDatabaseConnection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // pull from environment variables or fall back to a default configuration
    $host     = getenv('DB_HOST') ?: '127.0.0.1';
    $port     = (int)(getenv('DB_PORT') ?: 3306);
    $database = getenv('DB_NAME') ?: 'sweetwater_test';
    $user     = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $database);

    // pdo options for better error handling and performance
    $options = [];

    try {
        $pdo = new PDO($dsn, $user, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Output clean message and terminate execution if database is unreachable
        error_log('Database Connection Failed: ' . $e->getMessage());
        die('Database connection error. Check server logs.');
    }
}