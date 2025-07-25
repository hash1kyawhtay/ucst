<?php
session_start();
require_once('http://ucst.projecthub.backend/auth.php');

// Fetch contributors (users) from DB
require_once('http://ucst.projecthub.backend/db.php'); // Makes $pdo available

$contributorOptions = "";

try {
    $stmt = $pdo->prepare("SELECT id, username FROM users ORDER BY username ASC");
    $stmt->execute();
    $contributors = $stmt->fetchAll();

    foreach ($contributors as $row) {
        $contributorOptions .= "<option value='{$row['id']}'>{$row['username']}</option>";
    }
} catch (PDOException $e) {
    echo "Error fetching contributors: " . $e->getMessage();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $tags = isset($_POST['tags']) ? implode(',', (array)$_POST['tags']) : '';
    $year = $_POST['year'];
    $description = $_POST['description'];
    $code = $_POST['code'];
    $team = $_POST['team_name'];
    $contributors = isset($_POST['contributors']) ? implode(',', $_POST['contributors']) : '';
    $created_by = $_SESSION['user_id'] ?? null;

    // Cover Image
    $cover = '';
    if (!empty($_FILES['cover_image']['name'])) {
        $cover = basename($_FILES['cover_image']['name']);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], "../uploads/$cover");
    }

    // Documentation
    $doc = '';
    if (!empty($_FILES['documentation']['name'])) {
        $doc = basename($_FILES['documentation']['name']);
        move_uploaded_file($_FILES['documentation']['tmp_name'], "../uploads/$doc");
    }

    // Downloadable Files
    $file = '';
    if (!empty($_FILES['downloadable_files']['name'])) {
        $file = basename($_FILES['downloadable_files']['name']);
        move_uploaded_file($_FILES['downloadable_files']['tmp_name'], "../uploads/$file");
    }

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO projects 
        (title, tags, year, cover_image, description, code, downloadable_files, documentation, team_name, contributors, created_by) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([$title, $tags, $year, $cover, $description, $code, $file, $doc, $team, $contributors, $created_by]);

    header("Location: index.php?success=Project added successfully!");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .form-container {
      max-width: 720px;
      margin: 40px auto;
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<?php include('nav.php'); ?>

<div class="form-container">
  <h3 class="mb-4 text-center">📦 Add New Project</h3>
  <form method="POST" enctype="multipart/form-data">

    <!-- Title -->
    <div class="mb-3">
      <label for="title" class="form-label">Project Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <!-- Team Name -->
    <div class="mb-3">
      <label for="team" class="form-label">Team Name</label>
      <input type="text" class="form-control" id="team_name" name="team_name" required>
    </div>

   <!-- Contributors -->
    <div class="mb-3">
      <label for="contributors" class="form-label">Contributors</label>
      <select class="form-select" id="contributors" name="contributors[]" multiple required>
        <?= $contributorOptions ?>
      </select>
      <div class="form-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple contributors.</div>
    </div>

    <!-- Tags -->
    <!-- Tags -->
<div class="mb-3">
  <label class="form-label d-block">Tags</label>
  <div class="btn-group" role="group" aria-label="Tags">
    <?php
    $allTags = ['HTML', 'CSS', 'Java', 'PHP', 'C#', 'Python', 'NLP', 'ML', 'AI', 'JavaScript', 'C++'];
    foreach ($allTags as $tag) {
      echo "
        <input type='checkbox' class='btn-check' id='tag_$tag' name='tags[]' value='$tag'>
        <label class='btn btn-outline-primary mb-1' for='tag_$tag'>$tag</label>
      ";
    }
    ?>
  </div>
</div>


    <!-- Year -->
    <div class="mb-3">
      <label for="year" class="form-label">Academic Year</label>
      <select class="form-select" id="year" name="year" required>
        <option disabled selected value="">Choose a year</option>
        <option>First Year</option>
        <option>Second Year</option>
        <option>Third Year</option>
        <option>Fourth Year</option>
        <option>Fifth Year</option>
      </select>
    </div>

    <!-- Cover Image -->
    <div class="mb-3">
      <label for="cover" class="form-label">Cover Image</label>
      <input type="file" class="form-control" id="cover-image" name="cover_image" accept="image/*" required>
    </div>

    <!-- Description -->
    <div class="mb-3">
      <label for="description" class="form-label">Project Description</label>
      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>

    <!-- Code -->
    <div class="mb-3">
      <label for="code" class="form-label">Project Code (paste or describe)</label>
      <textarea class="form-control" id="code" name="code" rows="4" required></textarea>
    </div>

    <!-- Downloadable Files -->
    <div class="mb-3">
      <label for="files" class="form-label">Downloadable Project Files (ZIP)</label>
      <input type="file" class="form-control" id="downloadable_files" name="downloadable_files" accept=".zip,.rar,.7z" required>
    </div>

    <!-- Documentation -->
    <div class="mb-4">
      <label for="docs" class="form-label">Documentation (PDF/DOCX)</label>
      <input type="file" class="form-control" id="documentation" name="documentation" accept=".pdf,.doc,.docx" required>
    </div>

    <!-- Submit -->
    <div class="text-center">
      <button type="submit" class="btn btn-primary w-50">➕ Add Project</button>
    </div>
  </form>
</div>

</body>
</html>
