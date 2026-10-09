<?php
// config/db.php
// TODO: include the Database class
require_once __DIR__ . '/../classes/Database.php';

$dsn = "mysql:host=localhost;dbname=training_db;charset=utf8mb4";
$user = "root";
$pass = "";

// TODO: obtain the single shared PDO connection
// Hint: $db = Database::getInstance($dsn, $user, $pass);
$db = Database::getInstance($dsn, $user, $pass);

function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}