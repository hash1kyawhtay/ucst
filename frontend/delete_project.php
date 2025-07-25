<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {}
    if (!isset($_POST['id'])) {
        die("Project ID missing.");
    }

    $project_id = $_POST['id'];
    
require_once('http://ucst.projecthub.backend/db.php'); // Makes $conn available

    // Verify ownership before deleting
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :pid AND created_by = :uid");
    $stmt->execute([':pid' => $project_id, ':uid' => $user_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        die("Project not found or you don't have permission to delete it.");
    }

    // Delete project
    $delete = $pdo->prepare("DELETE FROM projects WHERE project_id = :pid AND created_by = :uid");
    $delete->execute([':pid' => $project_id, ':uid' => $user_id]);

    header("Location: dashboard.php?msg=Project+deleted+successfully");
    exit;
