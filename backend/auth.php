<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: http://ucst.projecthub/login.php");
    exit();
}
