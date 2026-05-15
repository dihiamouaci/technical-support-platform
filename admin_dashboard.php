<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .welcome-section {
            margin-top: 80px;
        }

        .images-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .images-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        @media (min-width: 768px) {
            .images-container {
                flex-direction: row;
            }

            .images-container img {
                height: 250px;
                object-fit: cover;
            }
        }

        .welcome-text h1 {
            font-weight: bold;
            font-size: 2.5rem;
        }

        .welcome-text p {
            font-size: 1.2rem;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease;
        }

        .welcome-text {
            padding-left: 12rem;
        }
    </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin - Gestion Tech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="#">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_users.php">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_reports.php">Rapports</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php">Tickets Support</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_settings.php">Paramètres</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_tasks.php">Gestion des Tâches</a></li>
            </ul>
            <span class="navbar-text text-white me-3">
                Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<!-- Section d'accueil -->
<div class="container welcome-section py-5">
    <div class="row align-items-center">
        <div class="col-md-6 images-container">
            <img src="admin_photo.jpg" alt="Image 1">
            <img src="admin_photo2.jpg" alt="Image 2">
        </div>
        <div class="col-md-6 welcome-text">
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?> ...</h1>
            <p>Bienvenue dans votre espace de gestion. Ici, vous pouvez surveiller l'activité, gérer les utilisateurs, traiter les tickets de support et consulter les rapports détaillés pour optimiser votre service.</p>
        </div>
    </div>
</div>

<!-- Cartes de navigation -->
<div class="container py-4">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 mb-3"></i>
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text">Gérez les comptes et les rôles des utilisateurs.</p>
                    <a href="admin_users.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart-line-fill display-4 mb-3"></i>
                    <h5 class="card-title">Rapports</h5>
                    <p class="card-text">Consultez les rapports d’activités et d’interventions.</p>
                    <a href="admin_reports.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-danger h-100">
                <div class="card-body text-center">
                    <i class="bi bi-life-preserver display-4 mb-3"></i>
                    <h5 class="card-title">Tickets Support</h5>
                    <p class="card-text">Surveillez et traitez les demandes de support.</p>
                    <a href="admin_support.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
        <!--  carte Gestion des Tâches -->
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-list-task display-4 mb-3"></i>
                    <h5 class="card-title">Gestion des Tâches</h5>
                    <p class="card-text">Attribuez et suivez les tâches des techniciens.</p>
                    <a href="admin_tasks.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
