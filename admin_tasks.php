<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Récupérer toutes les tâches avec le technicien associé
$stmt = $pdo->query("
    SELECT t.id_task, t.titre, t.status, t.date_echeance, u.prenom, u.nom
    FROM tasks t
    JOIN users u ON t.technicien_id = u.id
    ORDER BY t.date_echeance ASC
");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .page-header {
            margin-top: 100px;
            margin-bottom: 30px;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transition: 0.3s ease;
        }
    </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!-- Navbar reprise de admin_dashboard -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Admin - Gestion Tech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_users.php">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_reports.php">Rapports</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php">Tickets Support</a></li>
                <li class="nav-item"><a class="nav-link active" href="admin_tasks.php">Tâches</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_settings.php">Paramètres</a></li>
            </ul>
            <span class="navbar-text text-white me-3">
                Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container page-header">
    <h1 class="mb-4"><i class="bi bi-list-task"></i> Gestion des Tâches</h1>
    <a href="admin_add_task.php" class="btn btn-success mb-3">➕ Ajouter une tâche</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Technicien</th>
                        <th>Statut</th>
                        <th>Date d’échéance</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['id_task']) ?></td>
                        <td><?= htmlspecialchars($task['titre']) ?></td>
                        <td><?= htmlspecialchars($task['prenom'] . " " . $task['nom']) ?></td>
                        <td>
                            <?php if ($task['status'] === 'en cours'): ?>
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($task['status']) ?></span>
                            <?php elseif ($task['status'] === 'terminée'): ?>
                                <span class="badge bg-success"><?= htmlspecialchars($task['status']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($task['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($task['date_echeance']) ?></td>
                        <td class="text-center">
                            <a href="admin_edit_task.php?id=<?= $task['id_task'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="delete_task.php?id=<?= $task['id_task'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette tâche ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="6" class="text-center text-muted">Aucune tâche trouvée.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
