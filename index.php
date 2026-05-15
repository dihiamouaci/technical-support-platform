<?php
$title = "Accueil";
include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Gestion Tech</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body.dark-mode {
      background-color: #121212 !important;
      color: white !important;
    }

    .dark-mode h1, .dark-mode h2, .dark-mode h5, .dark-mode p, .dark-mode a, .dark-mode small, .dark-mode .lead {
      color: white !important;
    }

    .dark-mode .card {
      background-color: #1f1f1f !important;
      color: white !important;
    }

    .dark-mode .bg-light {
      background-color: #1a1a1a !important;
      color: white !important;
    }

    .dark-mode .btn-primary {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .dark-mode .btn-outline-primary {
      color: white;
      border-color: #0d6efd;
    }

    .dark-mode .btn-outline-primary:hover {
      background-color: #0d6efd;
      color: white;
    }

    .dark-mode footer.bg-dark {
      background-color: #000 !important;
    }

    .hero-section {
      padding: 4rem 0;
      background-color: #f8f9fa;
    }

    .dark-mode .hero-section {
      background-color: #1a1a1a;
    }

    .feature-img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-radius: 0.5rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .feature-box {
      margin-bottom: 2rem;
    }

    .feature-text {
      margin-top: 1rem;
    }

    .theme-toggle {
      position: fixed;
      top: 1rem;
      right: 1rem;
      cursor: pointer;
      font-size: 1.8rem;
      z-index: 9999;
      background: transparent;
      border: none;
      color: #333;
    }

    .dark-mode .theme-toggle {
      color: white;
    }
  </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>
<button class="theme-toggle" onclick="toggleTheme()"></button>

<!-- Section de bienvenue -->
<section class="hero-section text-center">
  <div class="container">
    <h1 class="fw-bold mb-4">Bienvenue chez SM NETWORK</h1>
    <p class="lead mb-5">
      Simplifiez la gestion de vos interventions, renforcez votre sécurité et assurez un suivi professionnel avec notre plateforme moderne.
    </p>
    <div class="d-flex justify-content-center gap-3">
      <a href="login.php" class="btn btn-primary btn-lg">Se connecter</a>
      <a href="register.php" class="btn btn-outline-primary btn-lg">Créer un compte</a>
    </div>
  </div>
</section>

<!-- Section avec 3 photos + texte -->
<section class="container py-5">
  <div class="row text-center">
    <div class="col-md-4 feature-box">
      <img src="Maintenance-alarme.webp" alt="Intervention rapide" class="feature-img mb-3">
      <h5>Interventions Efficaces</h5>
      <p class="feature-text">
        Nos techniciens qualifiés interviennent rapidement pour garantir la sécurité de vos locaux.
      </p>
    </div>
    <div class="col-md-4 feature-box">
      <img src="accuille_photo2.jpg" alt="Technologie avancée" class="feature-img mb-3">
      <h5>Technologie Avancée</h5>
      <p class="feature-text">
        Nous utilisons les derniers outils pour vous offrir une gestion centralisée, simple et performante.
      </p>
    </div>
    <div class="col-md-4 feature-box">
      <img src="accuille_photo.jpeg" alt="Sécurité assurée" class="feature-img mb-3">
      <h5>Sécurité Renforcée</h5>
      <p class="feature-text">
        Chaque donnée est protégée et chaque action est enregistrée pour une traçabilité complète.
      </p>
    </div>
  </div>
</section>

<!-- Section À propos -->
<section class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="mb-4">Pourquoi choisir SM NETWORK ?</h2>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Centralisation</h5>
            <p class="card-text">Gérez tous vos utilisateurs, tickets et rapports depuis une seule plateforme intuitive.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Support Réactif</h5>
            <p class="card-text">Notre équipe est disponible pour vous aider rapidement en cas de besoin.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Sécurité</h5>
            <p class="card-text">Toutes vos données sont protégées grâce à des protocoles de sécurité avancés.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
  <div class="container">
    <p class="mb-1">&copy; <?= date('Y') ?> SM NETWORK. Tous droits réservés.</p>
    <small>Développé avec ❤️ pour une gestion simplifiée</small>
  </div>
</footer>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");
    const toggle = document.querySelector(".theme-toggle");

    if (savedTheme === "dark") {
      document.body.classList.add("dark-mode");
      toggle.textContent = "☀️";
    } else {
      toggle.textContent = "🌙";
    }
  });

  function toggleTheme() {
    const body = document.body;
    const toggle = document.querySelector(".theme-toggle");
    const isDark = body.classList.toggle("dark-mode");
    toggle.textContent = isDark ? "☀️" : "🌙";
    localStorage.setItem("theme", isDark ? "dark" : "light");
  }
</script>

</body>
</html>
