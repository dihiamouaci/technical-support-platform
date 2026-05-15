<!-- header.php -->
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Bienvenue' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    :root {
      --primary: #1a237e; /* bleu marine */
      --primary-hover: #0d153f;
      --bg-light: #f4faff;
      --bg-dark: #121212;
      --text-light: #333;
      --text-dark: #f4f4f4;
      --card-bg-light: #fff;
      --card-bg-dark: #1e1e1e;
    }

    [data-theme="dark"] {
      --bg-light: var(--bg-dark);
      --text-light: var(--text-dark);
      --card-bg-light: var(--card-bg-dark);
    }

    body {
      margin: 0;
      padding-top: 60px;
      background-color: var(--bg-light);
      color: var(--text-light);
      font-family: 'Segoe UI', sans-serif;
      transition: background 0.3s, color 0.3s;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 60px;
      background-color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 25px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
      z-index: 1000;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .theme-toggle {
      font-size: 1.3em;
      cursor: pointer;
      margin-left: 20px;
    }

    .user {
      color: white;
      font-size: 0.95em;
      margin-left: 10px;
    }
  </style>
    <?php include 'theme_apply.php'; ?>
</head>

<body>
<header>
  <nav>
    <a href="index.php">Accueil</a>

    <?php if (!empty($_SESSION['user_id'])): ?>
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php">Tableau de bord</a>
      <?php else: ?>
        <a href="technicien_dashboard.php">Tableau de bord</a>
      <?php endif; ?>
      <a href="logout.php">Déconnexion</a>
      <span class="user">Bonjour, <?= htmlspecialchars($_SESSION['prenom']) ?></span>
    <?php else: ?>
      <a href="login.php">Connexion</a>
      <a href="register.php">Inscription</a>
    <?php endif; ?>
  </nav>

  <div class="theme-toggle" onclick="toggleTheme()">🌙</div>
</header>

<script>
  function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute("data-theme");
    const next = current === "light" ? "dark" : "light";
    html.setAttribute("data-theme", next);
    document.querySelector('.theme-toggle').textContent = next === "light" ? "🌙" : "☀️";
  }
</script>
