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

// Convertir en événements pour le calendrier
$events = [];
foreach ($tasks as $task) {
    if ($task['date_echeance']) {
        $events[] = [
            'title' => $task['titre'] . " (" . $task['status'] . ")",
            'start' => $task['date_echeance']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Planning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
  <style>
    #calendar {
      max-width: 900px;
      margin: 20px auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
  </style>
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
        <li class="nav-item"><a class="nav-link" href="mes-taches.php">Mes Tâches</a></li>
        <li class="nav-item"><a class="nav-link active" href="planning.php">Planning</a></li>
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
  <h1 class="mb-4 text-center"><i class="bi bi-calendar3"></i> Mon Planning</h1>

  <!-- Calendrier -->
  <div id="calendar"></div>

  <!-- Tableau récapitulatif -->
  <div class="mt-5">
    <h2><i class="bi bi-list-task"></i> Récapitulatif des Tâches</h2>
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
      <div class="alert alert-info">Aucune tâche planifiée pour le moment.</div>
    <?php endif; ?>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'fr',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      events: <?= json_encode($events) ?>
    });
    calendar.render();
  });
</script>
</body>
</html>
