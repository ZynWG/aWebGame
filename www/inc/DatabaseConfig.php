<?php

// Database connection
$dbHost = 'localhost';
$dbName = 'protocol_test';
$dbUser = 'protocol_test';
$dbPass = '}+-Mn8^[QczB';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}