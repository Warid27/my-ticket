<?php

function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $host = 'localhost';
        $dbname = 'eticket';
        $charset = 'utf8mb4';
        $username = 'root';
        $pass = '';

        $sql = "mysql:host=$host;dbname=$dbname;charset=$charset";

        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];

        $pdo = new PDO($sql, $username, $pass, $options);
    }

    return $pdo;
}