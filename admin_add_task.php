<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $date_echeance = $_POST['date_echeance'];
    $technicien_id = $_POST['technicien_id'];

    $stmt = $pdo->prepare("INSERT INTO tasks (titre, description, status, date_echeance, technicien_id) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $status, $date_echeance, $technicien_id]);

    header("Location: admin_tasks.php");
    exit;
}

// Récupérer les techniciens
$users = $pdo->query("SELECT id, prenom, nom FROM users WHERE role='technicien'")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une tâche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h1>Ajouter une tâche</h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="status" class="form-select">
                <option value="en attente">En attente</option>
                <option value="en cours">En cours</option>
                <option value="terminée">Terminée</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date d’échéance</label>
            <input type="date" name="date_echeance" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Technicien</label>
            <select name="technicien_id" class="form-select" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['prenom'] . " " . $user['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="admin_tasks.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
