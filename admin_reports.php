<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Gestion des messages flash pour les notifications Bootstrap
function set_flash($message, $type = 'success') {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function show_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo "<div class='alert alert-{$flash['type']} alert-dismissible fade show' role='alert'>" .
             htmlspecialchars($flash['message']) .
             "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    }
}

// Recherche, filtre, pagination
$search = trim($_GET['search'] ?? '');
$filterCategorie = $_GET['categorie'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(titre LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($filterCategorie !== '') {
    $where[] = "categorie = ?";
    $params[] = $filterCategorie;
}

$whereClause = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM reports $whereClause");
$totalStmt->execute($params);
$totalReports = $totalStmt->fetchColumn();
$totalPages = ceil($totalReports / $perPage);

$sql = "SELECT * FROM reports $whereClause ORDER BY date_creation DESC LIMIT $perPage OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajout ou modification
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie = $_POST['categorie'] ?? 'Général';
    $report_id = $_POST['report_id'] ?? null;

    if ($titre === '' || $description === '') {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        if ($report_id) {
            $stmt = $pdo->prepare("UPDATE reports SET titre = ?, description = ?, categorie = ? WHERE id = ?");
            $stmt->execute([$titre, $description, $categorie, $report_id]);
            set_flash("Rapport modifié avec succès.");
        } else {
            $stmt = $pdo->prepare("INSERT INTO reports (titre, description, categorie) VALUES (?, ?, ?)");
            $stmt->execute([$titre, $description, $categorie]);
            set_flash("Rapport ajouté avec succès.");
        }
        header('Location: admin_reports.php');
        exit;
    }
}

// Suppression
if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    set_flash("Rapport supprimé.", 'danger');
    header('Location: admin_reports.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'theme_apply.php'; ?>
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="container mt-5 pt-5">
  
    <?php show_flash(); ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="get" class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Recherche par titre ou description..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="categorie" class="form-select">
                <option value="">Toutes les catégories</option>
                <?php foreach (["Général", "Bug", "Amélioration", "Sécurité", "Performance"] as $cat): ?>
                    <option value="<?= $cat ?>" <?= $filterCategorie === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">Filtrer</button>
        </div>
        <div class="col-md-3">
            <a href="admin_reports.php" class="btn btn-outline-danger w-100">Réinitialiser</a>
        </div>
    </form>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#reportModal">Ajouter un rapport</button>
    <a href="export_excel.php" class="btn btn-success mb-3 ms-2">Export Excel</a>
    <a href="export_pdf.php" class="btn btn-danger mb-3 ms-2">Export PDF</a>

    <table class="table table-bordered">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Catégorie</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reports as $report): ?>
            <tr>
                <td><?= $report['id'] ?></td>
                <td><?= htmlspecialchars($report['titre']) ?></td>
                <td><?= htmlspecialchars($report['categorie']) ?></td>
                <td><?= htmlspecialchars($report['description']) ?></td>
                <td><?= $report['date_creation'] ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reportModal"
                            data-id="<?= $report['id'] ?>"
                            data-titre="<?= htmlspecialchars($report['titre'], ENT_QUOTES) ?>"
                            data-description="<?= htmlspecialchars($report['description'], ENT_QUOTES) ?>"
                            data-categorie="<?= htmlspecialchars($report['categorie'], ENT_QUOTES) ?>">
                        Modifier
                    </button>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Supprimer ce rapport ?');">
                        <input type="hidden" name="delete_id" value="<?= $report['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mb-3 d-flex gap-2">
          <a href="export_excel.php" class="btn btn-success">📄 Export Excel</a>
          <a href="export_pdf.php" class="btn btn-danger">📄 Export PDF</a>
    </div>


    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&categorie=<?= urlencode($filterCategorie) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Ajouter / Modifier un rapport</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="report_id" id="report_id">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="Général">Général</option>
                            <option value="Bug">Bug</option>
                            <option value="Amélioration">Amélioration</option>
                            <option value="Sécurité">Sécurité</option>
                            <option value="Performance">Performance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('reportModal');
    modal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var titre = button.getAttribute('data-titre');
        var description = button.getAttribute('data-description');
        var categorie = button.getAttribute('data-categorie');

        document.getElementById('report_id').value = id || '';
        document.getElementById('titre').value = titre || '';
        document.getElementById('description').value = description || '';
        document.getElementById('categorie').value = categorie || 'Général';
    });
});
</script>
</body>
</html>
