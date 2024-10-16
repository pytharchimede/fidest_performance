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
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style_dashboard.css" rel="stylesheet">
    <link href="plugins/css/montserrat" rel="stylesheet">
    <link href="css/style_all_dashboard.css" rel="stylesheet">
</head>

<body>

    <?php include 'menu.php'; ?>
    <div class="container-fluid mt-5">
        <?php if ($nbTachesExpired > 0): ?>
            <div data-intro="Cette zone d'alerte apparait uniquement lorsque certaines de vos taches n'ont pas été exécutées dans le delai imparti." class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Alerte !</strong> Vous avez <b data-intro="Vous pouvez voir ici le nombre de tâches à délais expirés."><?= $nbTachesExpired; ?></b> tâches avec un délai expiré.
                <a data-intro="...et un raccourci vers la liste de vos tâches." href="taches_en_attente.php" class="btn btn-warning btn-sm ml-3">
                    <i class="fas fa-tasks"></i> Voir les tâches expirées
                </a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <audio id="alert-sound" src="audio/alerte.mp3" preload="auto"></audio>
        <?php endif; ?>

        <div class="text-center mb-4">
            <button data-intro="Cliquez ici pour faire une demande de prêt d'absence, de prêt, d'heures supplementaires, d'avance sur salaire etc." class="btn btn-custom" id="demandeButton" data-toggle="modal" data-target="#demandeModal">
                <i class="fas fa-plus-circle mr-2"></i> Faire une demande
            </button>
        </div>

        <div class="row profile-section mb-4 d-flex align-items-center justify-content-between">
            <img data-intro="Assurez-vous que c'est bien votre photo qui figure ici sur votre interface et qu'elle semble assez professionnelle car celle-ci sera utilisée comme photo d'identité sur votre badge" src="<?php echo htmlspecialchars($_SESSION['photo_personnel_tasks'] ? 'https://stock.fidest.ci/app/&_gestion/photo/' . htmlspecialchars($_SESSION['photo_personnel_tasks']) : 'https://via.placeholder.com/80'); ?>" alt="Photo de profil">
            <div class="col-md-6 col-xs-12 profile-info">
                <h5 data-intro="Votre nom en entier doit également figurer ici. Veuillez signaler toute erreur d'orthographe si vous en remarquez car ce nom figurera sur votre contrat." class="text-primary"><?php echo strtoupper($_SESSION['nom_personnel_tasks']); ?></h5>
                <p data-intro="Ceci est votre fonction selon la base de donnée RH alors n'hésitez pas à faire une reclammation auprès du/de la gestionnaire des ressources humaines si vous contestez ce qui y figure." class="text-muted"><?= $fonction_personnel['lib_fonction_tasks'] ? $fonction_personnel['lib_fonction_tasks'] : 'Membre du personnel' ?></p>
                <p data-intro="Le service auquel vous êtes affectez paraît juste là." class="text-muted service-name">SERVICE <?= $service_personnel['lib_service_tasks'] ? strtoupper($service_personnel['lib_service_tasks']) : 'FIDEST/BANAMUR' ?></p>
            </div>
            <div class="col-md-6 col-xs-12 score-section">
                <h5 class="card-title text-secondary">Score</h5>
                <p data-intro="Ce pourcentage représente la fraction des tâches exécutées sur celles qui vous ont été assignées. Il ne rentre pas du tout dans les calculs du rang du salarié mais sert plutôt de boussole pour l'utilisateur connecté concernant ses propres objectifs." class="card-text <?= $scoreClass ?> display-4"><?= number_format(floatval($score), 2) ?>%</p>
                <div class="progress">
                    <div data-intro="Ce composant est s'appelle dans le jargon informatique 'Une progress-bar ou barre de progression en français' il représente de façon visuelle le pourcentage calculé précédemment." class="progress-bar <?= $progressBarClass ?>" role="progressbar"
                        style="width: <?= number_format(floatval($score), 2) ?>%"
                        aria-valuenow="<?= number_format(floatval($score), 2) ?>"
                        aria-valuemin="0" aria-valuemax="100">
                        <?= number_format(floatval($score), 2) ?>%
                    </div>
                </div>
                <h5 class="card-title text-secondary">Classement</h5>
                <p class="card-text display-4" data-intro="Ceci est votre rang parmi les salariés basé sur votre temps de travail, il est le plus important indicateur de performances car il se base sur la durée et le nombre de tâches exécutées.">
                    #<?= $ranking ?> <span data-intro="Vous avez ici l'effectif des personnes classées." class="ranking-effectif">/ <?= $effectif ?></span>
                </p>
            </div>
        </div>


        <div class="row">


            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div data-intro="Cette section récapitule les tâches assignées selon leurs respectifs états : En attente d'exécution, Exécutée effectivement ou rejetée" class="card-body">
                        <h5 class="card-title text-center text-primary fw-bold" data-intro="Ce titre indique la section des tâches.">Tâches</h5>
                        <hr class="my-3">
                        <ul class="list-group">
                            <li class="list-group-item en-attente" data-intro="Ici par exemple nous avons les tâches en attente d'exécution">
                                <i class="fas fa-hourglass-half"></i>
                                <a href="taches_en_attente.php" data-intro="Cliques ici pour accéder à la liste de vos tâches en attente.">En Attente:
                                    <span data-intro="Le nombre de tâches en attente" class="badge badge-warning float-right"><?php echo $nbTachesEnAttente; ?></span></a>
                            </li>
                            <li class="list-group-item effectuees" data-intro="Ici par exemple nous avons les tâches exécutées effectivement.">
                                <i class="fas fa-check-circle"></i>
                                <a href="taches_terminees.php" data-intro="Cliques ici pour accéder à la liste de vos tâches exécutées.">Effectuées:
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

            <div class="col-lg-4 col-md-6 mb-4" data-intro="Voici un aperçu des statistiques de vos tâches, réparties par statut.">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold" data-intro="Ce titre résume la section : Temps Total des Tâches.">Temps Total des Tâches</h5>
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12" data-intro="Le temps total des tâches qui sont actuellement en attente d'action.">
                                <div class="statistic-box bg-warning text-white rounded p-3">
                                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">En Attente</h6>
                                    <p class="fs-4"><?= $totalTimeEnAttenteHrs ?>h <?= $totalTimeEnAttenteMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12" data-intro="Le temps total des tâches déjà effectuées.">
                                <div class="statistic-box bg-success text-white rounded p-3">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">Effectuées</h6>
                                    <p class="fs-4"><?= $totalTimeEffectueesHrs ?>h <?= $totalTimeEffectueesMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12" data-intro="Le temps total des tâches qui ont été rejetées.">
                                <div class="statistic-box bg-danger text-white rounded p-3">
                                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                                    <h6 class="fw-time-type">Rejetées</h6>
                                    <p class="fs-4"><?= $totalTimeRejeteesHrs ?>h <?= $totalTimeRejeteesMin ?>m</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12" data-intro="Vous pouvez cliquer ici pour voir plus de détails sur vos tâches.">
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


            <div class="col-lg-4 col-md-12 col-xs-12 mb-4" data-intro="Cette section vous informe sur vos notifications récentes.">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary fw-bold" data-intro="Ce titre indique la section Notifications.">Notifications</h5>
                        <hr class="my-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item" data-intro="Vous pouvez voir ici combien de nouvelles notifications vous avez reçues.">
                                <i class="fas fa-bell text-info me-3"></i>
                                <span class="fw-semibold">Vous avez <?php $nbNotifications = 2;
                                                                    echo $nbNotifications; ?> nouvelles notifications</span>
                                <a href="liste_feb.php" class="btn btn-outline-info btn-sm float-right btn-notif" data-intro="Cliquez ici pour voir tous les détails de vos notifications.">Voir</a>
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
        <a data-intro="Cliquez sur ce boutton pour accéder à votre espace superviseur et suivre l'activité de tout le personnel." href="superviseur_dashboard.php" class="btn-float" title="Espace Superviseur">
            <i class="fas fa-user-tie"></i> Espace Superviseur
        </a>
    <?php } else { ?>
        <!-- Bouton flottant vers la fiche d'évaluation du personnel connecté -->
        <a data-intro="Cliquez sur ce boutton pour voir en un coup d'oeil votre évaluation en tant que memebre du personnel." href="https://fidest.ci/performance/profil_personnel_tasks.php?id=<?= $monId ?>" class="btn-float" title="Ma fiche d'évaluation">
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
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#alert-sound")[0].play();

            <?php if ($_SESSION['nombre_connection'] <= 2) { ?>
                introJs().start();
            <?php } ?>

            // Ajoute un événement pour démarrer intro.js lorsque le bouton est cliqué
            document.getElementById('startIntro').addEventListener('click', function() {
                introJs().start();
            });
        });
    </script>
    <script src="js/style_script.js"></script>
</body>

</html>