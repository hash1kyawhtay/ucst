<?php
if (!isset($_SESSION)) session_start();
require_once('http://ucst.projecthub.backend/db.php');

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!-- nav.php -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
  <a class="navbar-brand fw-bold" href="index.php">UCST Project Hub</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ucstNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-between" id="ucstNavbar">
    <ul class="navbar-nav mx-auto">
      <ul class="navbar-nav mx-auto">
</ul>
 
    </ul>

  <div class="d-flex align-items-center gap-3">
  <?php if ($user): ?>
    <!-- + Add Project button -->
    <a href="addproject.php" class="btn btn-outline-light fw-semibold">+ Add Project</a>

    <!-- Avatar dropdown -->
    <div class="dropdown">
      <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" style="width: 40px; height: 40px;">
        <span class="fw-bold text-primary text-uppercase"><?= substr($user['username'], 0, 1) ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li><h6 class="dropdown-header"><?= htmlspecialchars($user['username']) ?></h6></li>
        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
        <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="http://ucst.projecthub.backend/logout.php">Logout</a></li>
      </ul>
    </div>
  <?php else: ?>
    <a href="login.php" class="btn btn-outline-light">Login</a>
  <?php endif; ?>
</div>

</nav>
