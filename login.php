<?php
session_start();
require_once 'db.php';
 
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    if (!$email || !$motdepasse) {
        $erreur = "Tous les champs sont requis.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($motdepasse, $user['password'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['prenom'] = $user['prenom'];
          $_SESSION['role'] = $user['role'];

         //  Log de connexion réussie
         $log = $pdo->prepare("INSERT INTO logs (user_id, action, details) VALUES (?, 'Connexion réussie', ?)");
         $log->execute([$user['id'], 'Connexion depuis IP : ' . $_SERVER['REMOTE_ADDR']]);
         if ($user['role'] === 'admin') {
           header("Location: admin_dashboard.php");
         } else {
            header("Location: technicien_dashboard.php");
         }
        exit;
   } else {
    //  Log de tentative échouée
    $log = $pdo->prepare("INSERT INTO logs (user_id, action, details) VALUES (NULL, 'Connexion échouée', ?)");
    $log->execute(['Email saisi : ' . $email . ', IP : ' . $_SERVER['REMOTE_ADDR']]);

    $erreur = "Identifiants incorrects.";
  }

}
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --bg: #f4faff;
            --text: #333;
            --card-bg: #ffffff;
        }

        [data-theme='dark'] {
            --bg: #121212;
            --text: #f4f4f4;
            --card-bg: #1e1e1e;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .theme-toggle {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.4em;
            cursor: pointer;
        }

        .home-link {
            position: absolute;
            top: 15px;
            left: 20px;
        }

        .home-link a {
            text-decoration: none;
            font-weight: bold;
            background-color: transparent;
            border: 2px solid #007bff;
            padding: 6px 12px;
            border-radius: 6px;
            color: #007bff;
            transition: all 0.3s ease;
        }

        .home-link a:hover {
            background-color: #007bff;
            color: white;
        }

        .link {
            text-align: center;
            margin-top: 10px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<!--  Thème et  Accueil -->
<div class="theme-toggle" onclick="toggleTheme()">🌙</div>
<div class="home-link"><a href="index.php">🏠 Accueil</a></div>

<div class="container">
    <h2 style="text-align:center;">Connexion</h2>

    <?php if (!empty($erreur)): ?>
        <div class="error"><?= htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="motdepasse">Mot de passe</label>
        <input type="password" id="motdepasse" name="motdepasse" required>

        <button type="submit">Se connecter</button>
    </form>

    <div class="link">
        <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
    </div>
</div>

<script>
    function toggleTheme() {
        const html = document.documentElement;
        const current = html.getAttribute("data-theme");
        const next = current === "light" ? "dark" : "light";
        html.setAttribute("data-theme", next);
        document.querySelector('.theme-toggle').textContent = next === "light" ? "🌙" : "☀️";
    }
</script>
</body>
</html>
