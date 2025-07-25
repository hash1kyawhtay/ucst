<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <title>UCST Projects</title>
  <style>
        body {
            background: #f5f6fa;
        }
        .card-project {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .card-project:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .badge {
            margin-right: 4px;
        }
    </style>
</head>
<body>

<?php include('nav.php');
require_once('../backend/db.php');

$sql = "SELECT p.*, u.username AS aa 
        FROM projects p 
        JOIN users u ON p.created_by = u.id 
        ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = $_GET['query'] ?? '';
$language = $_GET['language'] ?? '';
$year = $_GET['year'] ?? '';

$sql = "SELECT * FROM projects WHERE 1=1";
$params = [];

if ($query) {
    $sql .= " AND title LIKE ?";
    $params[] = "%$query%";
}
if ($language) {
    $sql .= " AND tags = ?";
    $params[] = $language;
}
if ($year) {
    $sql .= " AND year = ?";
    $params[] = $year;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projects = $stmt->fetchAll();

?>

<div class="container my-4">
  <form method="GET" action="index.php">
    <div class="row g-3 align-items-end">
      
      <!-- Search -->
      <div class="col-md-4">
        <label class="form-label fw-semibold">Search Projects</label>
        <input type="text" class="form-control" name="query" placeholder="Enter project name or keyword">
      </div>
      
      <!-- Language Filter -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Language</label>
        <select class="form-select" name="language">
          <option value="">All</option>
          <option value="HTML">HTML</option>
          <option value="PHP">PHP</option>
          <option value="JavaScript">JavaScript</option>
          <option value="Python">Python</option>
          <option value="Java">Java</option>
          <!-- Add more as needed -->
        </select>
      </div>

      <!-- Year Filter -->
      <div class="col-md-3">
        <label class="form-label fw-semibold">Year</label>
        <select class="form-select" name="year">
          <option value="">All</option>
          <option value="First Year">First Year</option>
          <option value="Second Year">Second Year</option>
          <option value="Third Year">Third Year</option>
          <option value="Fourth Year">Fourth Year</option>
          <option value="Final Year">Final Year</option>
        </select>
      </div>

      <!-- Submit -->
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
      
    </div>
  </form>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($projects as $project): ?>
            <div class="col-md-4">
                <a href="view.php?id=<?= $project['project_id'] ?>" class="text-decoration-none text-dark">
                    <div class="card card-project h-100 shadow-sm" style="cursor: pointer;">
                        <img src="../uploads/<?= htmlspecialchars($project['cover_image']) ?>" class="card-img-top" alt="Cover Image" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                            <div class="mb-2">
                                <p class="text-muted mb-1">Tags: <?= htmlspecialchars($project['tags']) ?></p>
                            </div>
                            <p class="text-muted mb-1">Year: <?= htmlspecialchars($project['year']) ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
