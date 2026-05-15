<?php
session_start();
require_once 'db.php';

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';
    $role = $_POST['role'] ?? 'technicien';

    if (!$prenom || !$nom || !$email || !$motdepasse) {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Email invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreur = "Cet email est déjà utilisé.";
        } else {
            $hashed = password_hash($motdepasse, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, password, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$prenom, $nom, $email, $hashed, $role])) {
                $_SESSION['success'] = "Inscription réussie.";
                header("Location: login.php");
                exit;
            } else {
                $erreur = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --bg: #f4f4f4;
            --text: #333;
            --card-bg: #fff;
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

        input, select {
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
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
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
<!-- Bouton Thème -->
<div class="theme-toggle" onclick="toggleTheme()">🌙</div>

<!--  Bouton Accueil -->
<div style="position: absolute; top: 15px; left: 20px;">
    <a href="index.php" class="btn btn-outline-primary">🏠 Accueil</a>
</div>

<div class="container">
    <h2 style="text-align:center;">Inscription</h2>

    <?php if (!empty($erreur)): ?>
        <div class="error"><?= htmlspecialchars($erreur); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="motdepasse">Mot de passe</label>
        <input type="password" id="motdepasse" name="motdepasse" required>

        <label for="role">Rôle</label>
        <select id="role" name="role">
            <option value="technicien">Technicien</option>
            <option value="admin">Administrateur</option>
        </select>

        <button type="submit">S'inscrire</button>
    </form>

    <div class="link">
        <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
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
