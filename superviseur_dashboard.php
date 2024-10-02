<?php

session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

include('header_superviseur_dashboard.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Superviseur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/superviseur_style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <a class="navbar-brand text-primary" href="dashboard.php"><i class="fas fa-chart-line"></i> Tableau de Bord</a>


    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="liste_personnel.php">Personnel</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pointage_personnel.php">Pointage</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="taches_en_attente.php">Tâches</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="demandes_report.php">Demandes de report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </nav>

    <div class="container-fluid py-4">
        <!-- En-tête du tableau de bord -->
        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="dashboard-title">Tableau de bord du Superviseur</h1>
            </div>
        </div>

        <!-- Section pour afficher l'employé du mois -->
        <div class="row mb-5 justify-content-center">
            <div class="col-12 col-lg-8 mb-4">
                <div class="card shadow-lg p-4">
                    <div class="card-body text-center">
                        <h3 class="card-title text-primary">Employé du Mois</h3>
                        <div class="employee-photo-container position-relative mb-3">
                            <img src="https://stock.fidest.ci/app/&_gestion/photo/<?php echo $employe['photo_personnel_tasks']; ?>" alt="Photo de l'employé" class="img-fluid rounded-circle employee-photo shadow" width="180">
                            <span class="employee-crown position-absolute">
                                <i class="fas fa-crown text-warning fa-2x"></i>
                            </span>
                        </div>
                        <h4 class="text-dark"><?php echo $employe['nom_personnel_tasks']; ?></h4>

                        <!-- Temps Total Travaillé mis en évidence -->
                        <div class="worked-time my-3">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock"></i>
                            </span>
                            Temps total travaillé : 
                            <strong class="text-highlight">
                                <?php echo $maxWorkedTimeInHours; ?>
                            </strong>
                            <span class="hours-text">heures</span>
                        </div>

                        <div class="payment-invitation mb-3">
                            <p class="text-muted">Récompensez l'employé d'excellence avec une prime via :</p>
                        </div>
                        
                        <!-- Icônes de paiement -->
                        <div class="payment-icons d-flex justify-content-center mb-4">
                            <a href="#" class="payment-icon mx-2">
                                <img src="https://elephantech.ci/wp-content/uploads/2022/09/orange-money-logo.jpg" alt="Orange Money" class="img-fluid rounded">
                            </a>
                            <a href="#" class="payment-icon mx-2">
                                <img src="https://yop.l-frii.com/wp-content/uploads/2024/01/WAVE-recrute-pour-ce-poste-10-Janvier-2024.jpg" alt="Wave" class="img-fluid rounded">
                            </a>
                            <a href="#" class="payment-icon mx-2">
                                <img src="https://warehouse.canal-overseas.com/content/0001/07/ed21f02f1a158b1f43e5e57e640ec727f30ec0d1.png" alt="MTN Money" class="img-fluid rounded">
                            </a>
                            <a href="#" class="payment-icon mx-2">
                                <img src="https://pbs.twimg.com/profile_images/1567825623841755137/D4eG9XT6_400x400.png" alt="Djamo" class="img-fluid rounded">
                            </a>
                        </div>

                        <!-- Bouton d'invitation au paiement -->
                        <a href="#" class="btn btn-primary btn-lg">
                            Effectuer le Paiement <i class="fas fa-money-bill-wave"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Section des graphiques pour les tâches -->
        <div class="row mb-5">
            <div class="col-12 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Répartition des Tâches</h5>
                        <canvas id="taskChart"></canvas> <!-- Graphique circulaire -->
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Présences & Absences</h5>
                        <canvas id="attendanceChart"></canvas> <!-- Graphique à barres -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des présences -->
        <div class="row mb-5 justify-content-center">
            <div class="col-12 col-md-10 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Durée Moyenne des Tâches</h5>
                        <canvas id="durationChart"></canvas> <!-- Graphique en barre -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Classement du Personnel -->
    <div class="row mb-5 justify-content-center">
        <div class="col-12 col-md-10">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <h3 class="card-title text-primary text-center mb-4">Classement du Personnel</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">N° Mle</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col" class="text-center">Temps Travaillé (heures)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $rank = 1;
                                foreach($employees as $employee) {
                                    echo '<tr>';
                                    echo '<th scope="row" class="text-center">' . $rank . '</th>';
                                    echo '<th scope="row" class="text-center">' . strtoupper($employee['matricule_personnel_tasks']) . '</th>';
                                    echo '<td><img src="https://stock.fidest.ci/app/&_gestion/photo/' . $employee['photo_personnel_tasks'] . '" alt="Photo de l\'employé" class="rounded-circle img-fluid" width="60"></td>';
                                    echo '<td>' . strtoupper($employee['nom_personnel_tasks']) . '</td>';
                                    echo '<td class="text-center"><span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> ' . round($employee['totalWorkedTime'] / 3600, 2) . ' heures</span></td>';
                                    echo '<td>
                                            <a href="profil_personnel_tasks.php?id='.$employee["id_personnel_tasks"].'" class="btn btn-info btn-sm">Voir Détails</a>
                                            <a href="print_badge.php?id='.$employee["id_personnel_tasks"].'" class="btn btn-info btn-sm">Imprimer Badge</a>
                                          </td>';
                                    echo '</tr>';
                                    $rank++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="js/superviseur_script.js"></script> -->
    <script>

        // Récupération des données PHP dans JavaScript
        const percentTachesEnAttente = <?php echo $percentTachesEnAttente; ?>;
        const percentTachesOk = <?php echo $percentTachesOk; ?>;
        const percentTachesAnnulees = <?php echo $percentTachesAnnulees; ?>;

        // Graphique circulaire - Répartition des Tâches
        const ctx1 = document.getElementById('taskChart').getContext('2d');
        const taskChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['En Attente', 'Annulées', 'Effectuées'],
                datasets: [{
                    label: 'Tâches',
                    data: [percentTachesEnAttente, percentTachesAnnulees, percentTachesOk], // Utiliser les données dynamiques
                    backgroundColor: ['#fabd02', '#f34e4e', '#1d2b57'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });

// Graphique en barres - Durée Moyenne des Tâches
const taskNames = <?php echo json_encode($taskNames); ?>;
const taskDurationsInHours = <?php echo json_encode($taskDurationsInHours); ?>;

// Création du graphique en barres - Durée Moyenne des Tâches
const ctx2 = document.getElementById('durationChart').getContext('2d');
const durationChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: taskNames, // Utilisation des noms des tâches
        datasets: [{
            label: 'Durée en Heures',
            data: taskDurationsInHours, // Utilisation des durées converties en heures
            backgroundColor: '#1d2b57',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique à barres - Présences & Absences
const attendanceData = <?php echo json_encode($attendanceData); ?>;

const ctx3 = document.getElementById('attendanceChart').getContext('2d');
const attendanceChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['Présences', 'Retards', 'Absences'],
        datasets: [{
            label: 'Nombre',
            data: [attendanceData.presence+attendanceData.retard, attendanceData.retard, attendanceData.absence],
            backgroundColor: ['#1d2b57', '#fabd02', '#f34e4e'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
    </script>
</body>
</html>
