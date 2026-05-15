<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($titre === '' || $description === '') {
        $_SESSION['report_error'] = "Tous les champs sont obligatoires.";
        header('Location: admin_reports.php');
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO reports (titre, description) VALUES (?, ?)");
    $stmt->execute([$titre, $description]);

    header('Location: admin_reports.php');
    exit;
}
header('Location: admin_reports.php');
exit;
