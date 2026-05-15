<?php
session_start();
require_once 'db.php';

// Rediriger si non connecté ou pas technicien
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($titre && $description) {
        $stmt = $pdo->prepare("INSERT INTO tasks (technicien_id, titre, description, status) VALUES (?, ?, ?, 'en cours')");
        $stmt->execute([$_SESSION['user_id'], $titre, $description]);

        $message = "Tâche ajoutée avec succès.";
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Ajouter une nouvelle tâche</h2>

    <?php if ($message): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre de la tâche</label>
            <input type="text" class="form-control" id="titre" name="titre" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description de la tâche</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="mes-taches.php" class="btn btn-secondary">Retour</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
