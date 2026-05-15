<?php
// Applique le thème enregistré dans la table settings.
// Valeurs possibles : light / dark
if (!isset($pdo)) {
    require_once __DIR__ . '/db.php';
}

$app_theme = 'light';
try {
    $stmtTheme = $pdo->prepare("SELECT value FROM settings WHERE `key` = 'app_theme' LIMIT 1");
    $stmtTheme->execute();
    $savedTheme = $stmtTheme->fetchColumn();
    if ($savedTheme === 'dark') {
        $app_theme = 'dark';
    }
} catch (Throwable $e) {
    $app_theme = 'light';
}
?>
<style>
    body.theme-dark {
        background-color: #121212 !important;
        color: #f1f1f1 !important;
    }

    body.theme-dark .container,
    body.theme-dark .container-fluid {
        color: #f1f1f1 !important;
    }

    body.theme-dark .card,
    body.theme-dark .modal-content,
    body.theme-dark .tab-content,
    body.theme-dark .list-group-item,
    body.theme-dark .dropdown-menu {
        background-color: #1e1e1e !important;
        color: #f1f1f1 !important;
        border-color: #333 !important;
    }

    body.theme-dark .table {
        --bs-table-bg: #1e1e1e;
        --bs-table-color: #f1f1f1;
        --bs-table-border-color: #444;
        color: #f1f1f1 !important;
    }

    body.theme-dark .form-control,
    body.theme-dark .form-select,
    body.theme-dark textarea {
        background-color: #2a2a2a !important;
        color: #ffffff !important;
        border-color: #555 !important;
    }

    body.theme-dark .form-control::placeholder {
        color: #bbbbbb !important;
    }

    body.theme-dark .bg-light {
        background-color: #1e1e1e !important;
    }

    body.theme-dark .text-dark,
    body.theme-dark h1,
    body.theme-dark h2,
    body.theme-dark h3,
    body.theme-dark h4,
    body.theme-dark h5,
    body.theme-dark h6,
    body.theme-dark p,
    body.theme-dark label {
        color: #f1f1f1 !important;
    }

    body.theme-dark .nav-tabs .nav-link {
        color: #f1f1f1 !important;
        border-color: #444 !important;
    }

    body.theme-dark .nav-tabs .nav-link.active {
        background-color: #2a2a2a !important;
        color: #ffffff !important;
        border-color: #555 !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.classList.add('theme-<?= htmlspecialchars($app_theme, ENT_QUOTES) ?>');
        document.documentElement.setAttribute('data-bs-theme', '<?= htmlspecialchars($app_theme, ENT_QUOTES) ?>');
    });
</script>
