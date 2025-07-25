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
require_once('http://ucst.projecthub.backend/db.php');
$a=$_SESSION['user_id'];
$sql = "SELECT p.*, u.username AS creator
FROM projects p
JOIN users u ON p.created_by = u.id
WHERE u.id = $a
ORDER BY p.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
$query = $_GET['query'] ?? '';
$language = $_GET['language'] ?? '';
$year = $_GET['year'] ?? '';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Unauthorized access.");
}

$sql1 = "SELECT * FROM projects WHERE 1=1 AND created_by = $user_id";
$params = []; // Always filter by the logged-in user

if (!empty($query)) {
    $sql1 .= " AND title LIKE ? AND created_by = $user_id";
    $params[] = "%$query%";
}

if (!empty($language)) {
    $sql1 .= " AND tags = ? AND created_by = $user_id";
    $params[] = $language;
}

if (!empty($year)) {
    $sql1 .= " AND year = ? AND created_by = $user_id";
    $params[] = $year;
}

$stmt1 = $pdo->prepare($sql1);
$stmt1->execute($params);
$projects1 = $stmt1->fetchAll();
*/
?>



<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($projects as $project): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <a href="view.php?id=<?= $project['project_id'] ?>" class="text-decoration-none text-dark">
                        <img src="../uploads/<?= htmlspecialchars($project['cover_image']) ?>" 
                             class="card-img-top" 
                             alt="Cover Image" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($project['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>
                            <p class="text-muted mb-1"><strong>Tags:</strong> <?= htmlspecialchars($project['tags']) ?></p>
                            <p class="text-muted mb-1"><strong>Year:</strong> <?= htmlspecialchars($project['year']) ?></p>
                            <p class="text-muted"><strong>Creator:</strong> <?= htmlspecialchars($project['created_by']) ?></p>
                        </div>
                    </a>
                    <div class="card-footer d-flex justify-content-between bg-white border-top-0 pt-3">
                        <a href="edit_project.php?id=<?= $project['project_id'] ?>" class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>

                        <form method="post" action="delete_project.php" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            <input type="hidden" name="id" value="<?= $project['project_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


</body>
</html>
