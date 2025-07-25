<?php
session_start();
require_once('../backend/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['signup_username']);
    $email = trim($_POST['signup_email']);
    $password = password_hash($_POST['signup_password'], PASSWORD_DEFAULT);

    try {
        // Check for duplicate email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            die("❌ Email already registered.");
        }

        // Insert user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        header("Location: addproject.php");
        exit();

    } catch (PDOException $e) {
        die("Signup failed: " . $e->getMessage());
    }
}
