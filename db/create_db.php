<?php
$pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$pdo->exec("CREATE DATABASE IF NOT EXISTS eticket");
if ($pdo) {
    echo "Database 'eticket' created successfully!";
} else {
    echo "Database 'eticket' failed to create!";
}