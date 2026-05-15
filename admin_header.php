<!-- admin_header.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">GestionTech Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_users.php">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_reports.php">Rapports</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php">Support</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_settings.php">Paramètres</a></li>
            </ul>
            <div class="d-flex">
                <span class="navbar-text text-light me-3">👤 Admin</span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>
