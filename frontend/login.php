<?php
session_start();
require_once('http://ucst.projecthub.backend/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_email'])) {
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "❌ Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login / Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: #f0f4f8;
      font-family: 'Inter', sans-serif;
    }
    .auth-container {
      max-width: 400px;
      margin: 80px auto;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      padding: 2rem;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }
  </style>
</head>
<body>

<?php include('nav.php'); ?>

<div class="auth-container">
  <ul class="nav nav-tabs nav-fill mb-3" id="authTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
        Login
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab">
        Signup
      </button>
    </li>
  </ul>

  <div class="tab-content" id="authTabsContent">
    <!-- Login Form -->
    <div class="tab-pane fade show active" id="login" role="tabpanel">
      <form action="login.php" method="post">
        <div class="mb-3">
          <label for="loginEmail" class="form-label">Email</label>
          <input type="email" name="login_email" class="form-control" id="loginEmail" required>
        </div>
        <div class="mb-3">
          <label for="loginPassword" class="form-label">Password</label>
          <input type="password" name="login_password" class="form-control" id="loginPassword" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>

    <!-- Signup Form -->
    <div class="tab-pane fade" id="signup" role="tabpanel">
      <form action="signup.php" method="post">
        <div class="mb-3">
          <label for="signupUsername" class="form-label">Username</label>
          <input type="text" name="signup_username" class="form-control" id="signupUsername" required>
        </div>
        <div class="mb-3">
          <label for="signupEmail" class="form-label">Email</label>
          <input type="email" name="signup_email" class="form-control" id="signupEmail" required>
        </div>
        <div class="mb-3">
          <label for="signupPassword" class="form-label">Password</label>
          <input type="password" name="signup_password" class="form-control" id="signupPassword" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Signup</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
