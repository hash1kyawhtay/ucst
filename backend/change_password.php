<?php
session_start();
require_once('http://ucst.projecthub.backend/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: http://ucst.projecthub/login.php');
    exit;
}

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['change_error'] = "All fields are required.";
    header('Location: http://ucst.projecthub.backend/profile.php');
    exit;
}

if ($new_password !== $confirm_password) {
    $_SESSION['change_error'] = "New passwords do not match.";
    header('Location: http://ucst.projecthub.backend/profile.php');
    exit;
}

// Get current user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($current_password, $user['password'])) {
    $_SESSION['change_error'] = "Incorrect current password.";
    header('Location: http://ucst.projecthub.backend/profile.php');
    exit;
}

// Update password
$new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->execute([$new_hashed, $_SESSION['user_id']]);

$_SESSION['change_success'] = "Password updated successfully.";
header('Location: http://ucst.projecthub.backend/profile.php');
exit;
