<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

function get_setting($key) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = ? LIMIT 1");
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    return $value === false ? '' : $value;
}

function update_setting($key, $value) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
    $stmt->execute([$key, $value]);
}

$success = '';
$errors = [];
$active_tab = $_GET['tab'] ?? 'general';
$allowed_tabs = ['general', 'password', 'email', 'modules'];
if (!in_array($active_tab, $allowed_tabs, true)) {
    $active_tab = 'general';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'general') {
        $app_name = trim($_POST['app_name'] ?? '');
        $app_theme = $_POST['app_theme'] ?? 'light';
        if (!in_array($app_theme, ['light', 'dark'], true)) {
            $app_theme = 'light';
        }
        update_setting('app_name', $app_name !== '' ? $app_name : 'Gestion Techniciens');
        update_setting('app_theme', $app_theme);
        $success = "Paramètres généraux mis à jour.";
        $active_tab = 'general';
    }

    if ($action === 'password') {
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if (strlen($new) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($new !== $confirm) {
            $errors[] = "Les deux mots de passe ne correspondent pas.";
        } else {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $_SESSION['user_id']]);
            $success = "Mot de passe modifié avec succès.";
        }
        $active_tab = 'password';
    }

    if ($action === 'email') {
        update_setting('contact_email', trim($_POST['contact_email'] ?? ''));
        update_setting('smtp_host', trim($_POST['smtp_host'] ?? ''));
        update_setting('smtp_port', trim($_POST['smtp_port'] ?? ''));
        update_setting('smtp_user', trim($_POST['smtp_user'] ?? ''));
        update_setting('smtp_pass', $_POST['smtp_pass'] ?? '');
        $success = "Configuration email / SMTP enregistrée.";
        $active_tab = 'email';
    }

    if ($action === 'modules') {
        $modules = $_POST['modules'] ?? [];
        if (!is_array($modules)) {
            $modules = [];
        }
        $valid_modules = ['users', 'reports', 'support', 'tasks', 'planning'];
        $modules = array_values(array_intersect($valid_modules, $modules));
        update_setting('modules_enabled', json_encode($modules));
        $success = "Modules mis à jour.";
        $active_tab = 'modules';
    }
}

$app_name = get_setting('app_name') ?: 'Gestion Techniciens';
$app_theme = get_setting('app_theme') ?: 'light';
$contact_email = get_setting('contact_email');
$smtp_host = get_setting('smtp_host');
$smtp_port = get_setting('smtp_port');
$smtp_user = get_setting('smtp_user');
$smtp_pass = get_setting('smtp_pass');
$modules_enabled = json_decode(get_setting('modules_enabled') ?: '[]', true);
if (!is_array($modules_enabled)) {
    $modules_enabled = [];
}

function tab_class($tab, $active_tab) {
    return $tab === $active_tab ? 'settings-tab active' : 'settings-tab';
}
function pane_class($tab, $active_tab) {
    return $tab === $active_tab ? 'settings-pane active' : 'settings-pane';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
    <style>
        .settings-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 24px;
        }
        .settings-tab {
            border: 1px solid #dee2e6;
            border-bottom: none;
            background: #f8f9fa;
            padding: 10px 16px;
            border-radius: 10px 10px 0 0;
            cursor: pointer;
            color: #212529;
            text-decoration: none;
            font-weight: 500;
        }
        .settings-tab:hover { background: #e9ecef; color: #000; }
        .settings-tab.active { background: #0d6efd; color: #fff; border-color: #0d6efd; }
        .settings-pane { display: none; }
        .settings-pane.active { display: block; }
        .settings-card {
            border: 1px solid #dee2e6;
            border-radius: 14px;
            padding: 24px;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,.05);
        }
        body.theme-dark .settings-tab { background: #1f1f1f; color: #f1f1f1; border-color: #444; }
        body.theme-dark .settings-tab:hover { background: #2a2a2a; color: #fff; }
        body.theme-dark .settings-tab.active { background: #0d6efd; color: #fff; }
        body.theme-dark .settings-card { background: #1e1e1e; color: #f1f1f1; border-color: #444; }
    </style>
</head>
<body>
<?php include 'admin_header.php'; ?>

<div class="container mt-5 pt-4">
    <h2 class="mb-4">Paramètres</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>

    <div class="settings-tabs" role="tablist">
        <button class="<?= tab_class('general', $active_tab) ?>" type="button" data-tab="general">Général</button>
        <button class="<?= tab_class('password', $active_tab) ?>" type="button" data-tab="password">Mot de passe</button>
        <button class="<?= tab_class('email', $active_tab) ?>" type="button" data-tab="email">Email / SMTP</button>
        <button class="<?= tab_class('modules', $active_tab) ?>" type="button" data-tab="modules">Modules</button>
    </div>

    <div class="settings-card">
        <div class="<?= pane_class('general', $active_tab) ?>" id="general">
            <h4 class="mb-3">Paramètres généraux</h4>
            <form method="post" action="admin_settings.php?tab=general">
                <input type="hidden" name="action" value="general">
                <div class="mb-3">
                    <label class="form-label">Nom de l’application</label>
                    <input type="text" name="app_name" value="<?= htmlspecialchars($app_name) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Thème</label>
                    <select name="app_theme" class="form-select">
                        <option value="light" <?= $app_theme === 'light' ? 'selected' : '' ?>>Clair</option>
                        <option value="dark" <?= $app_theme === 'dark' ? 'selected' : '' ?>>Sombre</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>

        <div class="<?= pane_class('password', $active_tab) ?>" id="password">
            <h4 class="mb-3">Changer le mot de passe admin</h4>
            <form method="post" action="admin_settings.php?tab=password">
                <input type="hidden" name="action" value="password">
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="form-control" autocomplete="new-password">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" class="form-control" autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
            </form>
        </div>

        <div class="<?= pane_class('email', $active_tab) ?>" id="email">
            <h4 class="mb-3">Configuration Email / SMTP</h4>
            <form method="post" action="admin_settings.php?tab=email">
                <input type="hidden" name="action" value="email">
                <div class="mb-3">
                    <label class="form-label">Email de contact</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($contact_email) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Hôte SMTP</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($smtp_host) ?>" class="form-control" placeholder="smtp.gmail.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Port SMTP</label>
                    <input type="text" name="smtp_port" value="<?= htmlspecialchars($smtp_port) ?>" class="form-control" placeholder="587">
                </div>
                <div class="mb-3">
                    <label class="form-label">Utilisateur SMTP</label>
                    <input type="text" name="smtp_user" value="<?= htmlspecialchars($smtp_user) ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe SMTP</label>
                    <input type="password" name="smtp_pass" value="<?= htmlspecialchars($smtp_pass) ?>" class="form-control">
                </div>
                <button type="submit" class="btn btn-info">Sauvegarder Email / SMTP</button>
            </form>
            <p class="text-muted mt-3 mb-0">Ces paramètres sont enregistrés. Pour envoyer réellement des emails, il faut ensuite que le code d’envoi utilise ces valeurs.</p>
        </div>

        <div class="<?= pane_class('modules', $active_tab) ?>" id="modules">
            <h4 class="mb-3">Activer / désactiver les modules</h4>
            <form method="post" action="admin_settings.php?tab=modules">
                <input type="hidden" name="action" value="modules">
                <?php
                $all_modules = [
                    'users' => 'Gestion des utilisateurs',
                    'tasks' => 'Gestion des tâches',
                    'planning' => 'Planning',
                    'reports' => 'Rapports',
                    'support' => 'Support'
                ];
                foreach ($all_modules as $value => $label): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="modules[]" value="<?= htmlspecialchars($value) ?>" id="module_<?= htmlspecialchars($value) ?>" <?= in_array($value, $modules_enabled, true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="module_<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></label>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-secondary mt-3">Mettre à jour les modules</button>
            </form>
            <p class="text-muted mt-3 mb-0">Les choix sont sauvegardés. Pour masquer automatiquement les menus, il faut relier le header à cette configuration.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.settings-tab');
    const panes = document.querySelectorAll('.settings-pane');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            const target = tab.dataset.tab;
            tabs.forEach(t => t.classList.remove('active'));
            panes.forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            const pane = document.getElementById(target);
            if (pane) pane.classList.add('active');
            const url = new URL(window.location.href);
            url.searchParams.set('tab', target);
            window.history.replaceState({}, '', url);
        });
    });
});
</script>
</body>
</html>
