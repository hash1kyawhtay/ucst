<?php
session_start();
require_once('../backend/auth.php');
require_once('../backend/db.php');

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$e = htmlspecialchars($user['email']); // Sanitize email for output
$c = htmlspecialchars($user['created_at']); // Sanitize username for output
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('nav.php'); ?>

<div class="container mt-5">
  <h2>Your Profile</h2>
  <ul class="list-group mt-3">
    <li class="list-group-item"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
    <li class="list-group-item"><strong>Email:</strong> <?= $e ?></li>
    <li class="list-group-item"><strong>Joined:</strong> <?= $c ?></li>
  </ul>
  <hr class="my-4">
<h4>Change Password</h4>

<?php if (isset($_SESSION['change_success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['change_success'] ?></div>
  <?php unset($_SESSION['change_success']); ?>
<?php elseif (isset($_SESSION['change_error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['change_error'] ?></div>
  <?php unset($_SESSION['change_error']); ?>
<?php endif; ?>

<form method="POST" action="../backend/change_password.php">
  <div class="mb-3">
    <label for="current_password" class="form-label">Current Password</label>
    <input type="password" class="form-control" id="current_password" name="current_password" required>
  </div>
  <div class="mb-3">
    <label for="new_password" class="form-label">New Password</label>
    <input type="password" class="form-control" id="new_password" name="new_password" required>
  </div>
  <div class="mb-3">
    <label for="confirm_password" class="form-label">Confirm New Password</label>
    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
  </div>
  <button type="submit" class="btn btn-primary">Change Password</button>
</form>

</div>

</body>
</html>
