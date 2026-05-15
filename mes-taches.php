<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Récupérer les tâches du technicien
$stmt = $pdo->prepare("SELECT id_task, titre, description, status, date_echeance 
                       FROM tasks 
                       WHERE technicien_id = ?");
$stmt->execute([$userId]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Tâches</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!-- ✅ Navbar reprise de technicien_dashboard -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="technicien_dashboard.php">Technicien - Gestion Tech</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTech">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTech">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="technicien_dashboard.php">Tableau de bord</a></li>
        <li class="nav-item"><a class="nav-link active" href="mes-taches.php">Mes Tâches</a></li>
        <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
        <li class="nav-item"><a class="nav-link" href="support.php">Support</a></li>
      </ul>
      <span class="navbar-text text-white me-3">
        Bonjour, <?= htmlspecialchars($_SESSION['prenom']) ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
    </div>
  </div>
</nav>

<!-- ✅ Contenu principal -->
<div class="container mt-5">
  <h1 class="mb-4"><i class="bi bi-list-task"></i> Mes Tâches</h1>

  <?php if (count($tasks) > 0): ?>
    <table class="table table-striped table-bordered">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Titre</th>
          <th>Description</th>
          <th>Statut</th>
          <th>Date d’échéance</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tasks as $task): ?>
          <tr>
            <td><?= htmlspecialchars($task['id_task']) ?></td>
            <td><?= htmlspecialchars($task['titre']) ?></td>
            <td><?= htmlspecialchars($task['description']) ?></td>
            <td>
              <?php if ($task['status'] === 'en cours'): ?>
                <span class="badge bg-warning text-dark">En cours</span>
              <?php elseif ($task['status'] === 'terminée'): ?>
                <span class="badge bg-success">Terminée</span>
              <?php else: ?>
                <span class="badge bg-secondary">En attente</span>
              <?php endif; ?>
            </td>
            <td><?= $task['date_echeance'] ? htmlspecialchars($task['date_echeance']) : '-' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">Aucune tâche assignée pour le moment.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
