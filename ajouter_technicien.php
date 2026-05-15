<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Technicien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gestion Tech</a>
        <div class="d-flex">
            <span class="navbar-text text-white me-3">
                Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title">Bienvenue sur le tableau de bord Technicien</h3>
            <p class="card-text">Vous pouvez accéder ici à vos tâches, interventions, ou autres outils utiles.</p>

            <div class="mt-4">
                <a href="#" class="btn btn-primary">Voir mes interventions</a>
                <a href="#" class="btn btn-secondary">Modifier mon profil</a>
                <a href="#" class="btn btn-success">Ajouter un rapport</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
