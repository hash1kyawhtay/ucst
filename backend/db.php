<?php
$host = "localhost";
$dbname = "ucst";
$username = "root";
$password = "root"; // or "" depending on your MAMP settings

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Enable PDO error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
