<?php
session_start();
require_once '../model/DemandePret.php';

$demandePretObj = new DemandePret();

$date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : null;
$date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : null;
$statut = isset($_POST['statut']) ? $_POST['statut'] : 'En Attente'; // Par défaut "En Attente"

// Récupérer toutes les demandes de prêt
$demandes = $demandePretObj->lireDemandesPrets();

// Filtrer les demandes de prêt selon les critères fournis
if ($date_debut || $date_fin || $statut) {
    $demandes = array_filter($demandes, function ($demande) use ($date_debut, $date_fin, $statut) {
        // Filtrer par statut
        if ($statut && $demande['statut'] != $statut) {
            return false;
        }

        // Filtrer par date
        $dateCreation = $demande['date_creat'];
        if ($date_debut && $dateCreation < $date_debut) {
            return false;
        }
        if ($date_fin && $dateCreation > $date_fin) {
            return false;
        }

        return true; // Garder la demande si toutes les conditions sont satisfaites
    });
} else {
    // Si aucun filtre n'est appliqué, garder seulement les demandes en attente
    $demandes = array_filter($demandes, function ($demande) {
        return $demande['statut'] == 'En Attente';
    });
}

$nbDemandes = count($demandes);
?>

<ul class="list-group">
    <?php if ($nbDemandes > 0): ?>
        <?php foreach ($demandes as $demande): ?>
            <li class="list-group-item">
                <h5><i class="fas fa-credit-card"></i> <?= htmlspecialchars($demande['designation_pret']); ?></h5>
                <p><i class="fas fa-user"></i> Nom : <?= htmlspecialchars($demande['nom_prenom']); ?></p>
                <p><i class="fas fa-id-badge"></i> Matricule : <?= htmlspecialchars($demande['matricule']); ?></p>
                <p><i class="fas fa-money-bill-alt"></i> Montant demandé : <?= htmlspecialchars($demande['montant_demande']); ?> FCFA</p>
                <p><i class="fas fa-clock"></i> Statut : <?= htmlspecialchars($demande['statut']); ?></p>
                <p><i class="fas fa-calendar-alt"></i> Date de création : <?= htmlspecialchars($demande['date_creat']); ?></p>
                <div class="task-actions">
                    <!-- Bouton Exporter PDF -->
                    <a style="margin-top:19px;" href="request/export_demande_pret.php?id=<?= htmlspecialchars($demande['id']); ?>" class="btn btn-export">
                        <i class="fas fa-file-pdf"></i> Exporter
                    </a>

                    <!-- Bouton Approuver -->
                    <form method="post" action="request/traiter_demande.php" class="d-inline">
                        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demande['id']); ?>">
                        <button type="submit" name="action" value="approuver" class="btn btn-success">
                            <i class="fas fa-check"></i> Approuver
                        </button>
                    </form>

                    <!-- Bouton Rejeter -->
                    <form method="post" action="request/traiter_demande.php" class="d-inline">
                        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demande['id']); ?>">
                        <button type="submit" name="action" value="rejeter" class="btn btn-danger">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="no-deman-des">
            <div class="smiley">😊</div>
            <h3>Aucune demande de prêt trouvée</h3>
        </div>
    <?php endif; ?>
</ul>