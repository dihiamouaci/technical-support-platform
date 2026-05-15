<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Traitement ajout utilisateur
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Validation simple
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? '';

        if ($prenom === '' || $nom === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'technicien', 'utilisateur'])) {
            $errors[] = "Tous les champs sont obligatoires et doivent être valides.";
        } else {
            // Vérifier si email existe déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Un utilisateur avec cet email existe déjà.";
            } else {
                // Insérer nouvel utilisateur, mot de passe par défaut 'password' (à modifier)
                $password_hash = password_hash('password', PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (prenom, nom, email, role, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$prenom, $nom, $email, $role, $password_hash]);
                header('Location: admin_users.php');
                exit;
            }
        }
    }

    // Suppression utilisateur
    if (isset($_POST['delete_user_id'])) {
        $deleteId = (int) $_POST['delete_user_id'];
        // Ne pas permettre la suppression de soi-même
        if ($deleteId !== $_SESSION['user_id']) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$deleteId]);
            header('Location: admin_users.php');
            exit;
        } else {
            $errors[] = "Vous ne pouvez pas supprimer votre propre compte.";
        }
    }
}

// Récupérer utilisateurs
$stmt = $pdo->query("SELECT id, prenom, nom, email, role FROM users ORDER BY nom, prenom");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Utilisateurs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?php include 'theme_apply.php'; ?>
</head>
<body>
  <?php include 'admin_header.php'; ?>

<div class="container mt-5 pt-4">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulaire ajout utilisateur -->
    <div class="card mb-4">
        <div class="card-header">Ajouter un nouvel utilisateur</div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <input type="hidden" name="add_user" value="1" />
                <div class="col-md-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="col-md-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-md-2">
                    <label for="role" class="form-label">Rôle</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Choisir...</option>
                        <option value="admin">Admin</option>
                        <option value="technicien">Technicien</option>
                        <option value="utilisateur">Utilisateur</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['prenom']) ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                        <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                    <?php else: ?>
                    <span class="text-muted">Vous</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
