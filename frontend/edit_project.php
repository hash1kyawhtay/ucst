<?php
session_start();
require_once('http://ucst.projecthub.backend/auth.php');
require_once('http://ucst.projecthub.backend/db.php');

$project_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$project_id || !$user_id) {
    header('Location: dashboard.php');
    exit;
}

// Fetch project
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    die("Project not found.");
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $year = $_POST['year'] ?? '';
    $description = $_POST['description'] ?? '';
    $team_name = $_POST['team_name'] ?? '';
    $contributors = isset($_POST['contributors']) ? implode(',', $_POST['contributors']) : '';

    // Handle file uploads (optional updates)
    $cover_image = $project['cover_image'];
    if ($_FILES['cover_image']['size'] > 0) {
        $cover_image = 'uploads/' . uniqid() . '_' . $_FILES['cover_image']['name'];
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image);
    }

    $code = $project['code'];
    if ($_FILES['code']['size'] > 0) {
        $code = 'uploads/' . uniqid() . '_' . $_FILES['code']['name'];
        move_uploaded_file($_FILES['code']['tmp_name'], $code);
    }

    $downloadable_files = $project['downloadable_files'];
    if ($_FILES['downloadable_files']['size'] > 0) {
        $downloadable_files = 'uploads/' . uniqid() . '_' . $_FILES['downloadable_files']['name'];
        move_uploaded_file($_FILES['downloadable_files']['tmp_name'], $downloadable_files);
    }

    $documentation = $project['documentation'];
    if ($_FILES['documentation']['size'] > 0) {
        $documentation = 'uploads/' . uniqid() . '_' . $_FILES['documentation']['name'];
        move_uploaded_file($_FILES['documentation']['tmp_name'], $documentation);
    }

    $stmt = $pdo->prepare("UPDATE projects SET title=?, tags=?, year=?, description=?, team_name=?, contributors=?, cover_image=?, code=?, downloadable_files=?, documentation=? WHERE project_id=?");
    $stmt->execute([$title, $tags, $year, $description, $team_name, $contributors, $cover_image, $code, $downloadable_files, $documentation, $project_id]);

    header("Location: dashboard.php?msg=Project+updated");
    exit;
}

// Fetch all users for contributors dropdown
$users_stmt = $pdo->query("SELECT id, username FROM users");
$users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
$selected_contributors = explode(',', $project['contributors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('nav.php'); ?>

<div class="container mt-5">
  <h2>Edit Project</h2>
  <form method="post" enctype="multipart/form-data" class="mt-4">

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Tags</label>
      <input type="text" class="form-control" name="tags" value="<?= htmlspecialchars($project['tags']) ?>">
    </div>

    <div class="mb-3">
  <label class="form-label">Year</label>
  <select class="form-select" name="year" required>
    <?php
    $years = ['First Year', 'Second Year', 'Third Year', 'Final Year'];
    foreach ($years as $yearOption) {
        $selected = ($project['year'] == $yearOption) ? 'selected' : '';
        echo "<option value=\"$yearOption\" $selected>$yearOption</option>";
    }
    ?>
  </select>
</div>


    <div class="mb-3">
      <label class="form-label">Team Name</label>
      <input type="text" class="form-control" name="team_name" value="<?= htmlspecialchars($project['team_name']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Contributors</label>
      <select name="contributors[]" class="form-select" multiple>
        <?php foreach ($users as $u): ?>
          <option value="<?= $u['id'] ?>" <?= in_array($u['id'], $selected_contributors) ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['username']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description" rows="5"><?= htmlspecialchars($project['description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Cover Image</label><br>
      <?php if ($project['cover_image']): ?>
        <img src="../uploads/<?= $project['cover_image'] ?>" alt="Cover" style="max-width: 200px;"><br>
      <?php endif; ?>
      <input type="file" name="cover_image" class="form-control mt-2">
    </div>

    
    <div class="mb-3">
      <label class="form-label">Downloadable Files</label><br>
      <?php if ($project['downloadable_files']): ?>
        <a href="<?= $project['downloadable_files'] ?>" target="_blank">Download Current</a><br>
      <?php endif; ?>
      <input type="file" name="downloadable_files" class="form-control mt-2">
    </div>

    <div class="mb-3">
      <label class="form-label">Documentation (PDF/DOCX)</label><br>
      <?php if ($project['documentation']): ?>
        <a href="<?= $project['documentation'] ?>" target="_blank">View Current</a><br>
      <?php endif; ?>
      <input type="file" name="documentation" class="form-control mt-2">
    </div>

    <button type="submit" class="btn btn-primary">Update Project</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
