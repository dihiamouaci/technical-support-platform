<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Récupérer tous les tickets support
$stmt = $pdo->prepare("SELECT support.*, users.prenom, users.nom FROM support JOIN users ON support.technicien_id = users.id ORDER BY status = 'ouvert' DESC, date_creation DESC");
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Supprimer un ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ticket_id'])) {
    $deleteId = (int) $_POST['delete_ticket_id'];
    $stmt = $pdo->prepare("DELETE FROM support WHERE id_sup = ?");
    $stmt->execute([$deleteId]);
    header('Location: admin_support.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Support - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?php include 'theme_apply.php'; ?>
</head>
<body>
    <?php include 'admin_header.php'; ?>

<div class="container mt-5 pt-5">




    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Sujet</th>
                <th>Technicien</th>
                <th>Status</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id_sup']) ?></td>
                    <td><?= htmlspecialchars($ticket['sujet']) ?></td>
                    <td><?= htmlspecialchars($ticket['prenom'] . ' ' . $ticket['nom']) ?></td>
                    <td>
                        <?php if ($ticket['status'] === 'ouvert'): ?>
                            <button 
                                type="button" 
                                class="badge bg-success border-0" 
                                style="cursor:pointer;"
                                data-bs-toggle="modal" 
                                data-bs-target="#ticketModal<?= $ticket['id_sup'] ?>">
                                Ouvert
                            </button>
                        <?php else: ?>
                            <span class="badge bg-secondary">Fermé</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($ticket['date_creation']) ?></td>
                    <td>
                        <button 
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#ticketModal<?= $ticket['id_sup'] ?>">
                            Voir
                        </button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="ticketModal<?= $ticket['id_sup'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $ticket['id_sup'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post">
                                <input type="hidden" name="delete_ticket_id" value="<?= $ticket['id_sup'] ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel<?= $ticket['id_sup'] ?>">Ticket #<?= $ticket['id_sup'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Sujet :</strong> <?= htmlspecialchars($ticket['sujet']) ?></p>
                                    <p><strong>Technicien :</strong> <?= htmlspecialchars($ticket['prenom'] . ' ' . $ticket['nom']) ?></p>
                                    <p><strong>Date de création :</strong> <?= htmlspecialchars($ticket['date_creation']) ?></p>
                                    <p><strong>Status :</strong> <?= htmlspecialchars($ticket['status']) ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-danger">Supprimer le ticket</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
