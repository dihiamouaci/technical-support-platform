<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technicien') {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Récupérer les infos principales
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE technicien_id = ? AND status = 'en cours'");
$stmt->execute([$userId]);
$tasksCount = $stmt->fetchColumn();


$stmt = $pdo->prepare("SELECT COUNT(*) FROM support WHERE technicien_id = ? AND status = 'ouvert'");
$stmt->execute([$userId]);
$supportCount = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Technicien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }

        .welcome-section { margin-top: 80px; }

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
            .images-container { flex-direction: row; }
            .images-container img { height: 250px; object-fit: cover; }
        }

        .welcome-text h1 { font-weight: bold; font-size: 2.3rem; }
        .welcome-text p { font-size: 1.2rem; }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease;
        }

        .welcome-text {
           padding-left: 6rem !important; 
        }
    </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Technicien - Gestion Tech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTech">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTech">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="#">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="mes-taches.php">Mes Tâches</a></li>
                <li class="nav-item"><a class="nav-link" href="planning.php">Planning</a></li>
                <li class="nav-item"><a class="nav-link" href="support.php">Support</a></li>
            </ul>
            <span class="navbar-text text-white me-3">
                Bonjour, <?php echo htmlspecialchars($_SESSION['prenom']); ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Se déconnecter</a>
        </div>
    </div>
</nav>

<!-- Section d'accueil avec image gauche + texte droite -->
<div class="container welcome-section py-5">
    <div class="row align-items-center">
         <div class="col-md-6 images-container me-4">
            <img src="tech_photo.jpeg" alt="Technicien 1">
            <img src="Maintenance-alarme.webp" alt="Technicien 2">
        </div>
        <div class="col-md-5 welcome-text ps-6">
            <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom']); ?> ...</h1>
            <p>Dans cet espace, vous pouvez consulter vos tâches en cours, vos missions à venir, organiser votre planning et répondre aux demandes de support en toute simplicité.</p>
        </div>
    </div>
</div>

<!-- Cartes de navigation -->
<div class="container py-4">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-list-check display-4 mb-3"></i>
                    <h5 class="card-title">Mes Tâches</h5>
                    <p class="card-text">Vous avez <strong><?php echo $tasksCount; ?></strong> tâches en cours.</p>
                    <a href="mes-taches.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
        
                    
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-warning h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-event-fill display-4 mb-3"></i>
                    <h5 class="card-title">Planning</h5>
                    <p class="card-text">Consultez vos prochains événements.</p>
                    <a href="planning.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-hover border-0 shadow-sm text-white bg-info h-100">
                <div class="card-body text-center">
                    <i class="bi bi-life-preserver display-4 mb-3"></i>
                    <h5 class="card-title">Support</h5>
                    <p class="card-text">Vous avez <strong><?php echo $supportCount; ?></strong> tickets ouverts.</p>
                    <a href="support.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
