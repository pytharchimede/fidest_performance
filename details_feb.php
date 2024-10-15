<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_besoin'] != 1) {
    header('Location: acces_refuse.php');
}

require_once 'model/Database.php';
require_once 'model/FicheExpressionBesoin.php';
require_once 'model/Service.php';
require_once 'model/Personnel.php';
require_once 'model/BesoinExpression.php';
require_once 'model/BesoinExpressionFiles.php';



// Instanciations
$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

$serviceObj = new Service();
$personnelObj = new Personnel();
$ficheExpression = new FicheExpressionBesoin($pdo);
$besoinObj = new BesoinExpression($pdo);
$expressionBesoinFiles = new BesoinExpressionFiles($pdo);

// Récupérer l'ID de la fiche à partir de la requête GET
$ficheId = $_GET['id'];
$fiche = $ficheExpression->obtenirFicheParId($ficheId);
$demandeur = $personnelObj->getPersonnelByMatricule($fiche['matricule']);
$service = $serviceObj->obtenirServiceParId($fiche['departement']);
$besoins = $besoinObj->getBesoinsByFicheId($ficheId);
$files = $expressionBesoinFiles->getFilesByFicheId($ficheId);


// Données pour les fichiers justificatifs (exemple)
$fichiersJustificatifs = $files;

$isAccepterDisabled = false;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Fiche d'Expression de Besoin</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style_detail_besoin.css">
</head>

<body>

    <?php include 'menu.php'; ?>


    <div class="container">
        <h1 class="page-title">Détails de la Fiche d'Expression de Besoin</h1>

        <hr>
        <h5 class="highlight">IDENTIFICATION DU DEMANDEUR</h5>
        <hr>

        <div class="details-section">
            <span>Demandeur : <strong><?= htmlspecialchars($demandeur['nom_personnel_tasks']) ?></strong> | </span>
            <span>Service : <strong><?= htmlspecialchars($service['lib_service_tasks']) ?></strong> | </span>
            <span>Date de Création : <strong><?= htmlspecialchars($fiche['date']) ?></strong> | </span>
            <span>Montant : <strong><?= number_format($fiche['montant'], 2) ?> FCFA</strong></span>
        </div>

        <hr>
        <h5 class="highlight">BESOINS</h5>
        <hr>

        <a href="export_excel_devis.php?id=<?= $ficheId ?>" class="btn btn-success btn-lg" style="width: 50%; margin-bottom: 20px;">
            <i class="fas fa-file-excel"></i> Extraire le Devis
        </a>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Désignation</th>
                        <th>Qte</th>
                        <th>PU (FCFA)</th>
                        <th>Total (FCFA)</th>
                        <th>Action</th> <!-- Colonne pour le bouton Modifier -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalGeneral = 0;
                    $i = 0;
                    foreach ($besoins as $besoin) {
                        $prixTotal = $besoin['quantite'] * $besoin['prix_unitaire'];
                        $totalGeneral += $prixTotal;
                        $i++;

                        if ($besoin['prix_unitaire'] <= 0) {
                            $isAccepterDisabled = true;
                        }
                    ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= htmlspecialchars($besoin['objet']) ?></td>
                            <td>
                                <?= htmlspecialchars($besoin['quantite'] > 0 ? $besoin['quantite'] : '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> A définir</span>') ?>
                            </td>
                            <td>
                                <?= $besoin['prix_unitaire'] > 0 ? number_format($besoin['prix_unitaire'], 2) : '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> A définir</span>' ?>
                            </td>
                            <td>
                                <?= $prixTotal ? number_format($prixTotal, 2) : '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> A définir</span>' ?>
                            </td>
                            <td>
                                <button class="btn btn-warning blink"
                                    title="Veuillez définir la quantité ou le prix unitaire."
                                    onclick="openEditPopup(
        <?= $besoin['id'] ?? '' ?>, 
        '<?= $besoin['objet'] ?? '' ?>', 
        <?= $besoin['quantite'] ?? 0 ?>, 
        <?= $besoin['prix_unitaire'] ?? 0 ?>, 
        '<?= $besoin['nom_fournisseur'] ?? '' ?>', 
        '<?= $besoin['telephone'] ?? '' ?>'
    )">
                                    <i class="fa fa-edit"></i>
                                </button>

                            </td>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="total">Total Général : <?= number_format($totalGeneral, 2) ?> FCFA</div>

        <hr>
        <h5 class="highlight">DOCUMENTS JUSTIFICATIFS</h5>
        <hr>
        <?php foreach ($fichiersJustificatifs as $fichier): ?>
            <div class="card">
                <div class="card-header">
                    <?= htmlspecialchars($fichier['file_path']) ?>
                </div>
                <div class="card-body">
                    <a href="request/<?= htmlspecialchars($fichier['file_path']) ?>" target="_blank">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($isAccepterDisabled): ?>
            <div class="alert alert-warning" role="alert">
                Attention : Un ou plusieurs besoins n'ont pas de prix défini. Veuillez les corriger avant de continuer.
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="liste_feb.php" class="btn-custom"><i class="fas fa-arrow-left"></i> Retour</a>
            <?php if ($_SESSION['valid_besoin'] == 1) { ?>
                <a href="request/refuser_feb.php?id=<?= $ficheId ?>" class="btn-custom" style="background-color: #dc3545;">Refuser</a>
                <a href="request/accepter_feb.php?id=<?= $ficheId ?>" class="btn-custom <?= $isAccepterDisabled ? 'disabled' : '' ?>" style="background-color: <?= $isAccepterDisabled ? '#6c757d' : '#28a745' ?>;" title="<?= $isAccepterDisabled ? 'Un ou plusieurs besoins n\'ont pas de prix défini.' : '' ?>">
                    Accepter
                    <?php if ($isAccepterDisabled): ?>
                        <span class="tooltip" style="margin-left: 5px;">(Besoins sans prix)</span>
                    <?php endif; ?>
                </a>
            <?php } ?>
        </div>

    </div>

    <!-- Popup pour modifier le besoin -->
    <div id="editPopup" class="popup" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 1px solid #ccc; padding: 20px; z-index: 1001;">
        <h5>Modifier le Besoin</h5>
        <form id="editForm">
            <input type="hidden" name="id" id="editId" value="">

            <div class="form-group">
                <label for="editObjet">Désignation</label>
                <input type="text" class="form-control" id="editObjet" name="objet" readonly required>
            </div>
            <div class="form-group">
                <label for="editQuantite">Quantité</label>
                <input type="number" class="form-control" id="editQuantite" name="quantite" required>
            </div>
            <div class="form-group">
                <label for="editPrixUnitaire">Prix Unitaire (FCFA)</label>
                <input type="number" class="form-control" id="editPrixUnitaire" name="prix_unitaire" required>
            </div>

            <div class="form-group">
                <label for="editFournisseur">Fournisseur</label>
                <input type="text" class="form-control" id="editFournisseur" name="fournisseur" required>
            </div>

            <div class="form-group">
                <label for="editTelephone">Téléphone</label>
                <input type="text" class="form-control" id="editTelephone" name="telephone" required>
            </div>

            <button type="submit" class="btn btn-primary">Sauvegarder</button>
            <button type="button" class="btn btn-secondary" onclick="closeEditPopup()">Annuler</button>
        </form>
    </div>



    <!-- Bouton d'impression flottant -->
    <button id="printButton" class="btn btn-primary" onclick="window.location.href='request/export_details_besoin.php?id=<?= $ficheId ?>';" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        <i class="fas fa-print"></i> Imprimer
    </button>


    <script src="plugins/js/bootstrap.min.js"></script>
    <script src="js/function_detail_besoin.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>