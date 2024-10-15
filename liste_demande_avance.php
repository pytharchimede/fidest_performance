<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_avance'] != 1) {
    header('Location: acces_refuse.php');
}

require_once 'model/DemandeAvance.php';

// Instanciation de la classe DemandeAvance
$demandeAvance = new DemandeAvance();

// Récupérer toutes les demandes d'avance
$demandes = $demandeAvance->lireDemandesAvances();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes d'Avance</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        .card {
            border: none;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .statut-en-attente {
            background-color: #f9c74f;
            color: white;
        }

        .statut-accepte {
            background-color: #43aa8b;
            color: white;
        }

        .statut-refuse {
            background-color: #f94144;
            color: white;
        }

        .btn-export {
            background-color: #457b9d;
            color: white;
            margin-bottom: 20px;
        }

        .icon-large {
            font-size: 24px;
        }

        .no-demandes {
            text-align: center;
            margin-top: 50px;
            font-size: 1.5em;
        }

        .container-fluid {
            padding-top: 70px;
            /* Ajustez cette valeur selon la hauteur de votre navbar */
        }
    </style>
</head>

<body>

    <?php include 'menu.php'; ?>


    <div class="container-fluid mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <form id="searchForm">
                    <div class="form-row align-items-end">
                        <div class="col-md-2">
                            <select id="statut" class="form-control">
                                <option value="">Sélectionner l'état</option>
                                <option value="En Attente" selected>En Attente</option> <!-- Par défaut sélectionné -->
                                <option value="Acceptee">Acceptée</option>
                                <option value="Refusee">Refusée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="matricule" class="form-control" placeholder="Matricule">
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="date_debut" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="date_fin" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Rechercher</button>
                        </div>

                    </div>
                </form>
                <div class="row">
                    <div class="col-md-3">
                        <button style="margin-top:17px;" type="button" id="exportButton" class="btn btn-danger"><i class="fas fa-file-export"></i> Exporter la liste en PDF</button>
                    </div>
                    <div class="col-md-3">
                        <button style="margin-top:17px;" type="button" id="exportExcelButton" class="btn btn-success"><i class="fas fa-file-excel"></i> Exporter la liste en Excel</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="row" id="results">
            <?php if (empty($demandes)): ?>
                <div class="no-demandes">
                    <div class="smiley">😊</div>
                    <h3>Aucune demande d'avance trouvée</h3>
                </div>
            <?php else: ?>
                <?php foreach ($demandes as $demande): ?>
                    <div class="col-md-4">
                        <div class="card <?= strtolower(str_replace(' ', '-', $demande['statut'])); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($demande['nom']); ?></h5>
                                <p class="card-text">Matricule : <?= htmlspecialchars($demande['matricule']); ?></p>
                                <p class="card-text">Montant demandé : <?= htmlspecialchars($demande['montant']); ?> FCFA</p>
                                <p class="card-text">Statut : <?= htmlspecialchars($demande['statut']); ?></p>
                                <p class="card-text">Date de création : <?= htmlspecialchars($demande['date_creat']); ?></p>
                                <div class="task-actions">
                                    <a style="margin-top:19px;" target="_blank" href="request/export_demande_avance.php?id=<?= htmlspecialchars($demande['id']); ?>" class="btn btn-export">Exporter</a>
                                    <form method="post" action="request/traiter_demande.php" class="d-inline">
                                        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demande['id']); ?>">
                                        <button type="submit" name="action" value="approuver" class="btn btn-success">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                    </form>
                                    <form method="post" action="request/traiter_demande.php" class="d-inline">
                                        <input type="hidden" name="demande_id" value="<?= htmlspecialchars($demande['id']); ?>">
                                        <button type="submit" name="action" value="rejeter" class="btn btn-danger">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="plugins/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').submit(function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                // Faire une requête AJAX avec les valeurs des champs de recherche
                $.ajax({
                    url: 'ajax/search_demande_avance.php',
                    method: 'POST',
                    data: {
                        statut: statut,
                        matricule: matricule,
                        date_debut: dateDebut,
                        date_fin: dateFin
                    },
                    success: function(response) {
                        $('#results').html(response);
                    }
                });
            });

            $('#exportButton').click(function() {
                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                window.location.href = 'request/export_all_demande_avance.php?statut=' + statut + '&matricule=' + matricule + '&date_debut=' + dateDebut + '&date_fin=' + dateFin;
            });

            $('#exportExcelButton').click(function() {
                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                window.location.href = 'request/excel_export_demande_avance.php?statut=' + statut + '&matricule=' + matricule + '&date_debut=' + dateDebut + '&date_fin=' + dateFin;
            });
        });
    </script>

    <script src="js/style_script.js"></script>
</body>

</html>