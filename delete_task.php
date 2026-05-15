<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_tasks.php");
    exit;
}

$id_task = $_GET['id'];

// Supprimer la tâche
$stmt = $pdo->prepare("DELETE FROM tasks WHERE id_task = ?");
$stmt->execute([$id_task]);

header("Location: admin_tasks.php");
exit;
?>
