<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id_task'])) {
    $tacheId = (int)$_GET['id_task'];
    $technicienId = $_SESSION['user_id'];

    // Vérifier que la tâche appartient bien au technicien connecté
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id_task = ? AND technicien_id = ?");
    $stmt->execute([$tacheId, $technicienId]);
    $tache = $stmt->fetch();

    if ($tache) {
        // Supprimer la tâche
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id_task = ?");
        $stmt->execute([$tacheId]);

        header("Location: mes-taches.php?success=1");
        exit;
    } else {
        header("Location: mes-taches.php?error=unauthorized");
        exit;
    }
} else {
    header("Location: mes-taches.php?error=missing_id");
    exit;
}
?>
