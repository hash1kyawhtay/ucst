<?php
if (!isset($_SESSION)) session_start();
require_once('db.php');

$projects = [];


    // No WHERE clause, just get all projects
    $stmt = $pdo->prepare("SELECT * FROM projects");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


// $projects now contains all projects from the table
?>
