<?
require_once('db.php');
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM projects");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($projects);
    echo "</pre>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>