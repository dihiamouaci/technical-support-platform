<?php
session_start();

// Vérifie que l'utilisateur est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'db.php'; // Connexion à la base

try {
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email FROM users WHERE role = 'technicien'");
    $stmt->execute();
    $techniciens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de récupération des techniciens : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Techniciens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Liste des Techniciens</h2>
        <a href="ajouter_technicien.php" class="btn btn-primary">➕ Ajouter un technicien</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($techniciens as $tech): ?>
                <tr>
                    <td><?= htmlspecialchars($tech['id']) ?></td>
                    <td><?= htmlspecialchars($tech['nom']) ?></td>
                    <td><?= htmlspecialchars($tech['prenom']) ?></td>
                    <td><?= htmlspecialchars($tech['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin-dashboard.php" class="btn btn-secondary mt-3">← Retour au dashboard</a>
</div>

</body>
</html>
