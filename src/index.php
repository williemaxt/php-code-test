<?php

include_once __DIR__ . '/../data/db_connection.php';

$pdo = getDatabaseConnection();

echo "Database connection established successfully.";
echo "PDO driver name: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
