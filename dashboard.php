<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

include('header_dashboard.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Salarié</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style_dashboard.css" rel="stylesheet">
    <link href="plugins/css/montserrat" rel="stylesheet">

    <!-- <link href="css/style_dashboard_pc_light.css" rel="stylesheet">
    <link href="css/style_dashboard_pc_dark.css" rel="stylesheet"> -->
    <style>
        #modeToggle {
            margin-left: 10px;
            /* Espace entre le bouton et le reste de la barre de navigation */
            border: none;
            /* Supprime la bordure du bouton */
            background-color: transparent;
            /* Rendre le fond transparent */
            color: #007bff;
            /* Couleur des icônes */
            transition: color 0.3s;
            /* Transition pour un effet au survol */
        }

        #modeToggle:hover {
            color: #0056b3;
            /* Couleur au survol */
        }

        .service-name {
            font-size: xx-small;
            color: #0F0F0F00;
        }

        .mode-toggle-container {
            display: flex;
            align-items: center;
            /* Aligne l'icône et le texte */
            margin-left: 20px;
            /* Espace entre le menu et le bouton */
        }

        .btn {
            border-radius: 20px;
            /* Arrondit les bords du bouton */
            padding: 10px 15px;
            /* Ajuste le rembourrage pour un aspect plus subtil */
            transition: background-color 0.3s ease;
            /* Ajoute une transition douce */
        }

        .btn-light {
            background-color: #ffffff;
            /* Couleur de fond du bouton */
            color: #007bff;
            /* Couleur du texte */
            border: 1px solid #007bff;
            /* Bordure bleue */
        }

        .btn-light:hover {
            background-color: #f8f9fa;
            /* Couleur au survol */
        }

        .btn-light i {
            font-size: 1.2rem;
            /* Taille de l'icône */
        }

        span {
            font-size: 0.9rem;
            /* Taille de police du texte */
        }

        /* Styles généraux pour la navbar */
        .navbar {
            background-color: #ffffff;
            /* Couleur de fond de la navbar */
            padding: 10px 20px;
            /* Rembourrage */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Ombre légère */
            transition: background-color 0.3s;
            /* Transition douce */
            z-index: 1000;
            /* Pour s'assurer qu'elle reste au-dessus des autres éléments */
        }

        /* Styles pour les liens du menu */
        .navbar-nav .nav-item {
            margin-left: 20px;
            /* Espacement entre les éléments */
        }

        .navbar-nav .nav-link {
            color: #007bff;
            /* Couleur du texte des liens */
            font-weight: bold;
            /* Met le texte en gras */
            transition: color 0.3s;
            /* Transition douce pour le survol */
        }

        .navbar-nav .nav-link:hover {
            color: #0056b3;
            /* Couleur au survol */
            text-decoration: underline;
            /* Sous-ligne au survol */
        }

        /* Styles pour le bouton de basculement de mode */
        .mode-toggle-container {
            margin-left: 20px;
            /* Espacement entre le menu et le bouton */
        }

        .btn {
            border-radius: 20px;
            /* Arrondit les bords du bouton */
            padding: 8px 12px;
            /* Ajuste le rembourrage */
            transition: background-color 0.3s ease;
            /* Transition douce */
        }

        .btn-light {
            background-color: #ffffff;
            /* Couleur de fond du bouton */
            color: #007bff;
            /* Couleur du texte */
            border: 1px solid #007bff;
            /* Bordure bleue */
        }

        .btn-light:hover {
            background-color: #f1f1f1;
            /* Couleur au survol */
        }

        /* Styles pour les icônes */
        .navbar-toggler {
            border: none;
            /* Supprime la bordure du bouton */
        }

        .navbar-toggler-icon {
            background-color: #007bff;
            /* Couleur de fond de l'icône du bouton */
        }

        /* Effet de changement de couleur au survol */
        .navbar-toggler:hover .navbar-toggler-icon {
            background-color: #0056b3;
            /* Couleur au survol */
        }

        /* Styles pour le conteneur principal */
        .container-fluid {
            padding-top: 70px;
            /* Ajustez cette valeur selon la hauteur de votre navbar */
        }
    </style>

</head>

<body>

    <?php include 'menu.php'; ?>


    <div class="container-fluid mt-5">
        <?php if ($nbTachesExpired > 0): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Alerte !</strong> Vous avez <?= $nbTachesExpired; ?> tâches avec un délai expiré.
                <a href="taches_en_attente.php" class="btn btn-warning btn-sm ml-3">
                    <i class="fas fa-tasks"></i> Voir les tâches expirées
                </a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <audio id="alert-sound" src="audio/alerte.mp3" preload="auto"></audio>
        <?php endif; ?>

        <div class="text-center mb-4">
            <button class="btn btn-custom" id="demandeButton" data-toggle="modal" data-target="#demandeModal">
                <i class="fas fa-plus-circle mr-2"></i> Faire une demande
            </button>
        </div>

        <div class="row profile-section mb-4 d-flex align-items-center justify-content-between">
            <img src="<?php echo htmlspecialchars($_SESSION['photo_personnel_tasks'] ? 'https://stock.fidest.ci/app/&_gestion/photo/' . htmlspecialchars($_SESSION['photo_personnel_tasks']) : 'https://via.placeholder.com/80'); ?>" alt="Photo de profil">
            <div class="col-md-6 col-xs-12 profile-info">
                <h5 class="text-primary"><?php echo strtoupper($_SESSION['nom_personnel_tasks']); ?></h5>
                <p class="text-muted"><?= $fonction_personnel['lib_fonction_tasks'] ? $fonction_personnel['lib_fonction_tasks'] : 'Membre du personnel' ?></p>
                <p class="text-muted service-name">SERVICE <?= $service_personnel['lib_service_tasks'] ? strtoupper($service_personnel['lib_service_tasks']) : 'FIDEST/BANAMUR' ?></p>
            </div>
            <div class="col-md-6 col-xs-12 score-section">
                <h5 class="card-title text-secondary">Score</h5>
                <p class="card-text <?= $scoreClass ?> display-4"><?= number_format(floatval($score), 2) ?>%</p>
                <div class="progress">
                    <div class="progress-bar <?= $progressBarClass ?>" role="progressbar"
                        style="width: <?= number_format(floatval($score), 2) ?>%"
                        aria-valuenow="<?= number_format(floatval($score), 2) ?>"
                        aria-valuemin="0" aria-valuemax="100">
                        <?= number_format(floatval($score), 2) ?>%
                    </div>
                </div>
                <h5 class="card-title text-secondary">Classement</h5>
                <p class="card-text display-4">
                    #<?= $ranking ?> <span class="ranking-effectif">/ <?= $effectif ?></span>
                </p>
            </div>
        </div>


        <div class="row">


            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary fw-bold">Tâches</h5>
                        <hr class="my-3">
                        <ul class="list-group">
                            <li class="list-group-item en-attente">
                                <i class="fas fa-hourglass-half"></i>
                                <a href="taches_en_attente.php">En Attente:
                                    <span class="badge badge-warning float-right"><?php echo $nbTachesEnAttente; ?></span></a>
                            </li>
                            <li class="list-group-item effectuees">
                                <i class="fas fa-check-circle"></i>
                                <a href="taches_terminees.php">Effectuées:
                                    <span class="badge badge-success float-right"><?php echo $nbTachesOk; ?></span></a>
                            </li>
                            <li class="list-group-item rejetees">
                                <i class="fas fa-times-circle"></i>
                                <h5 class="card-title">Rejetées:
                                    <span class="badge badge-danger float-right"><?php echo $nbTachesRefusees; ?></span>
                                </h5>
                            </li>
                        </ul>
                        <a href="taches_en_attente.php" class="btn btn-custom btn-block mt-3">Voir plus</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">Temps Total des Tâches</h5>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="statistic-box bg-warning text-white rounded p-3">
                                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">En Attente</h6>
                                    <p class="fs-4"><?= $totalTimeEnAttenteHrs ?>h <?= $totalTimeEnAttenteMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="statistic-box bg-success text-white rounded p-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">Effectuées</h6>
                                    <p class="fs-4"><?= $totalTimeEffectueesHrs ?>h <?= $totalTimeEffectueesMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="statistic-box bg-danger text-white rounded p-3">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">Rejetées</h6>
                                    <p class="fs-4"><?= $totalTimeRejeteesHrs ?>h <?= $totalTimeRejeteesMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="statistic-box bg-info text-white rounded p-3">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">Voir plus</h6>
                                    <p class="fs-4">&nbsp;</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary fw-bold">Notifications</h5>
                        <hr class="my-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-bell text-info me-3"></i>
                                <span class="fw-semibold">Vous avez <?php $nbNotifications = 2;
                                                                    echo $nbNotifications; ?> nouvelles notifications</span>
                                <a href="liste_feb.php" class="btn btn-outline-info btn-sm float-right btn-notif">Voir</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>


        <!-- Modal pour faire une demande -->
        <div class="modal fade" id="demandeModal" tabindex="-1" role="dialog" aria-labelledby="demandeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title color-white" id="demandeModalLabel">Faire une Demande</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item btn-custom">
                                <a href="formulaire_introuvable.php?nom_form=absence" class="d-flex align-items-center btn-link-custom">
                                    <i class="fas fa-user-slash icon-custom"></i> Demande de Permission d'Absence
                                </a>
                            </li>
                            <li class="list-group-item btn-custom">
                                <a href="formulaire_introuvable.php?nom_form=pret" class="d-flex align-items-center btn-link-custom">
                                    <i class="fas fa-hand-holding-usd icon-custom"></i> Demande de Prêt
                                </a>
                            </li>
                            <!-- Demande d'Avance (désactivée avant le 15 du mois) -->
                            <li class="list-group-item btn-custom">
                                <?php if ($currentDay >= 15): ?>
                                    <a href="formulaire_introuvable.php?nom_form=avance" class="d-flex align-items-center btn-link-custom">
                                        <i class="fas fa-money-bill-alt icon-custom"></i> Demande d'Avance
                                    </a>
                                <?php else: ?>
                                    <a href="javascript:void();" class="d-flex align-items-center text-muted btn-link-disabled" title="Disponible à partir du 15">
                                        <i class="fas fa-money-bill-alt icon-custom"></i> Demande d'Avance (disponible à partir le 15)
                                    </a>
                                <?php endif; ?>
                            </li>
                            <li class="list-group-item btn-custom">
                                <a href="feb/index.php" class="d-flex align-items-center btn-link-custom">
                                    <i class="fas fa-plane icon-custom"></i> Fiche d'expression de besoin (FEB)
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if ($_SESSION['is_directeur'] == 1) { ?>
        <!-- Bouton flottant vers l'espace superviseur -->
        <a href="superviseur_dashboard.php" class="btn-float" title="Espace Superviseur">
            <i class="fas fa-user-tie"></i> Espace Superviseur
        </a>
    <?php } else { ?>
        <!-- Bouton flottant vers la fiche d'évaluation du personnel connecté -->
        <a href="https://fidest.ci/performance/profil_personnel_tasks.php?id=<?= $monId ?>" class="btn-float" title="Ma fiche d'évaluation">
            <i class="fas fa-file-pdf"></i> Ma fiche d'évaluation
        </a>
    <?php } ?>


    <!-- Alerte gênante pour tâches expirées -->
    <script>
        // Vérifier s'il y a des tâches expirées et afficher une alerte
        var nbTachesExpired = <?php echo $nbTachesExpired; ?>;
        if (nbTachesExpired > 0) {
            alert('Vous avez ' + nbTachesExpired + ' tâche(s) avec délai expiré!');
        }
    </script>

    <script src="plugins/js/jquery-3.5.1.slim.min.js"></script>
    <script src="plugins/js/popper.min.js"></script>
    <script src="plugins/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#alert-sound")[0].play();
        });
    </script>
    <script src="js/style_script.js"></script>
</body>

</html>