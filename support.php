<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Insertion d’un nouveau ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sujet'])) {
    $sujet = htmlspecialchars($_POST['sujet']);
    $stmt = $pdo->prepare("INSERT INTO support (sujet, technicien_id) VALUES (?, ?)");
    $stmt->execute([$sujet, $userId]);
    header("Location: support.php");
    exit;
}

// Récupération des tickets du technicien
$stmt = $pdo->prepare("SELECT * FROM support WHERE technicien_id = ? ORDER BY date_creation DESC");
$stmt->execute([$userId]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Support Technique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
        }
        .ticket-card {
            margin-bottom: 20px;
        }
    </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!-- ✅ Navbar reprise de technicien_dashboard -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="technicien_dashboard.php">Technicien - Gestion Tech</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTech">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTech">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="technicien_dashboard.php">Tableau de bord</a></li>
        <li class="nav-item"><a class="nav-link" href="mes-taches.php">Mes Tâches</a></li>
        <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
        <li class="nav-item"><a class="nav-link active" href="support.php">Support</a></li>
      </ul>
      <span class="navbar-text text-white me-3">
        Bonjour, <?= htmlspecialchars($_SESSION['prenom']) ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
    </div>
  </div>
</nav>

<!-- ✅ Contenu principal -->
<div class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-life-preserver"></i> Mes Tickets de Support</h2>

    <!-- Formulaire d'ajout de ticket -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="sujet" class="form-label">Sujet du ticket</label>
            <input type="text" class="form-control" id="sujet" name="sujet" required>
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>

    <!-- Liste des tickets -->
    <?php if (count($tickets) > 0): ?>
        <?php foreach ($tickets as $ticket): ?>
            <div class="card ticket-card border-<?= $ticket['status'] === 'ouvert' ? 'info' : 'secondary' ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($ticket['sujet']) ?></h5>
                    <p class="card-text">
                        <strong>Statut :</strong> <?= ucfirst($ticket['status']) ?><br>
                        <small class="text-muted">Créé le <?= date('d/m/Y à H:i', strtotime($ticket['date_creation'])) ?></small>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Aucun ticket de support trouvé.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
