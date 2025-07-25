<?php
require_once('../backend/db.php');

if (!isset($_GET['id'])) {
    echo "No project ID provided.";
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$project) {
    echo "Project not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($project['title']) ?> - Project Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .project-cover {
            max-width: 400px;
            max-height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin: 0 auto;
            display: block;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .section {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; 

// Split contributor IDs from comma-separated string
$contributor_ids = explode(',', $project['contributors']);

// Clean and prepare for SQL IN clause
$contributor_ids = array_map('intval', $contributor_ids); // convert to integers
$placeholders = rtrim(str_repeat('?,', count($contributor_ids)), ',');

// Fetch usernames
$query = "SELECT username FROM users WHERE id IN ($placeholders)";
$stmt = $pdo->prepare($query);
$stmt->execute($contributor_ids);
$usernames = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>


<div class="container py-5">
    <h2 class="mb-4 text-center"><?= htmlspecialchars($project['title']) ?></h2>

    <img src="../uploads/<?= htmlspecialchars($project['cover_image']) ?>" class="project-cover mb-4" alt="Cover Image">

    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="section">
                <p class="detail-label">Team Name:</p>
                <p><?= htmlspecialchars($project['team_name']) ?></p>
            </div>

            <div class="section">
    <p class="detail-label">Contributors:</p>
    <p><?= htmlspecialchars(implode(', ', $usernames)) ?></p>
</div>



            <div class="section">
                <p class="detail-label">Tags:</p>
                <p><?= htmlspecialchars($project['tags']) ?></p>
            </div>

            <div class="section">
                <p class="detail-label">Year:</p>
                <p><?= htmlspecialchars($project['year']) ?></p>
            </div>

            <div class="section">
                <p class="detail-label">Created By:</p>
                <p>
                    <?php
$id1 = $project['created_by'];
$stmt1 = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt1->execute([$id1]);
$project1 = $stmt1->fetch(PDO::FETCH_ASSOC);

echo htmlspecialchars($project1['username']);
?>

                </p>
            </div>

            <div class="section">
                <p class="detail-label">Created At:</p>
                <p><?= htmlspecialchars($project['created_at']) ?></p>
            </div>

            <div class="section">
                <p class="detail-label">Description:</p>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
            </div>

            <div class="section">
                <p class="detail-label">Code:</p>
                <pre style="background:#f8f9fa;padding:10px;border-radius:8px;"><?= htmlspecialchars($project['code']) ?></pre>
            </div>

            <?php if (!empty($project['documentation'])): ?>
                <div class="section">
                    <p class="detail-label">Documentation:</p>
                    <a href="../uploads/<?= htmlspecialchars($project['documentation']) ?>" class="btn btn-outline-primary" download>Download Documentation</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($project['downloadable_files'])): ?>
                <div class="section">
                    <p class="detail-label">Downloadable Files:</p>
                    <a href="../uploads/<?= htmlspecialchars($project['downloadable_files']) ?>" class="btn btn-outline-success" download>Download Project Files</a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>
