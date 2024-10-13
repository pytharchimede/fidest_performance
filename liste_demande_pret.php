<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
    header('Location: acces_refuse.php');
}

require_once 'model/DemandePret.php';

// Instanciation de la classe DemandePret
$demandePret = new DemandePret();

// Récupérer toutes les demandes de prêt
$demandes = $demandePret->lireDemandesPrets();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de Prêt</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        /**Demandes cards */
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link active" href="liste_personnel.php">Personnel</a></li>
                <li class="nav-item"><a class="nav-link" href="pointage_personnel.php">Pointage</a></li>
                <li class="nav-item"><a class="nav-link" href="taches_en_attente.php">Tâches</a></li>
                <li class="nav-item"><a class="nav-link" href="demandes_report.php">Demandes de report</a></li>
                <li class="nav-item"><a class="nav-link" href="liste_demande_avance.php">Demandes d'avances</a></li>
                <li class="nav-item"><a class="nav-link" href="liste_demande_pret.php">Demandes de prêt</a></li>
                <li class="nav-item"><a class="nav-link" href="liste_demande_absence.php">Demandes d'absence</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
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
                    <h3>Aucune demande de prêt trouvée</h3>
                </div>
            <?php else: ?>
                <?php foreach ($demandes as $demande): ?>
                    <div class="col-md-4">
                        <div class="card <?= strtolower(str_replace(' ', '-', $demande['statut'])); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($demande['designation_pret']); ?></h5>
                                <p class="card-text">Nom : <?= htmlspecialchars($demande['nom_prenom']); ?></p>
                                <p class="card-text">Matricule : <?= htmlspecialchars($demande['matricule']); ?></p>
                                <p class="card-text">Montant demandé : <?= htmlspecialchars($demande['montant_demande']); ?> FCFA</p>
                                <p class="card-text">Statut : <?= htmlspecialchars($demande['statut']); ?></p>
                                <p class="card-text">Date de création : <?= htmlspecialchars($demande['date_creat']); ?></p>
                                <div class="task-actions">
                                    <a style="margin-top:19px;" href="request/export_demande_pret.php?id=<?= htmlspecialchars($demande['id']); ?>" class="btn btn-export">Exporter</a>
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


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').submit(function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                console.log({
                    statut: statut,
                    matricule: matricule,
                    date_debut: dateDebut,
                    date_fin: dateFin
                });

                $.ajax({
                    url: 'ajax/search_demande_pret.php', // Le fichier PHP qui gère la recherche
                    type: 'POST',
                    data: {
                        statut: statut,
                        matricule: matricule,
                        date_debut: dateDebut,
                        date_fin: dateFin
                    },
                    success: function(response) {
                        $('#results').html(response); // Affiche les résultats dans le div "results"
                    }
                });
            });

            $('#exportButton').click(function() {
                // Récupérer les valeurs des champs
                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                console.log({
                    statut: statut,
                    matricule: matricule,
                    date_debut: dateDebut,
                    date_fin: dateFin
                });

                // Construire l'URL avec les paramètres
                let url = 'request/export_all_demande_pret.php?';
                if (statut) url += 'statut=' + encodeURIComponent(statut) + '&';
                if (matricule) url += 'matricule=' + encodeURIComponent(matricule) + '&';
                if (dateDebut) url += 'date_debut=' + encodeURIComponent(dateDebut) + '&';
                if (dateFin) url += 'date_fin=' + encodeURIComponent(dateFin) + '&';

                // Enlever le dernier '&' si présent
                url = url.slice(0, -1);

                console.log(url); // Pour déboguer

                // Ouvrir l'URL dans un nouvel onglet
                window.open(url, '_blank');
            });

            $('#exportExcelButton').click(function() {
                // Récupérer les valeurs des champs
                const statut = $('#statut').val();
                const matricule = $('#matricule').val();
                const dateDebut = $('#date_debut').val();
                const dateFin = $('#date_fin').val();

                // Construire l'URL pour l'exportation Excel
                let url = 'request/excel_export_demande_pret.php?';
                if (statut) url += 'statut=' + encodeURIComponent(statut) + '&';
                if (matricule) url += 'matricule=' + encodeURIComponent(matricule) + '&';
                if (dateDebut) url += 'date_debut=' + encodeURIComponent(dateDebut) + '&';
                if (dateFin) url += 'date_fin=' + encodeURIComponent(dateFin) + '&';

                // Enlever le dernier '&' si présent
                url = url.slice(0, -1);

                // Ouvrir l'URL dans un nouvel onglet
                window.open(url, '_blank');
            });



        });
    </script>
</body>

</html>