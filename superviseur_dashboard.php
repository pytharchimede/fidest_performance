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

          <!-- Section pour afficher les informations de l'employé #1 -->
          <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Employé du mois</h5>
                        <img src="https://stock.fidest.ci/app/&_gestion/photo/<?php echo $employe['photo_personnel_tasks']; ?>" alt="Photo de l'employé" class="img-fluid rounded-circle mb-3" width="150">
                        <h6><?php echo $employe['nom_personnel_tasks']; ?></h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des graphiques pour les tâches -->
        <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Répartition des Tâches</h5>
                        <canvas id="taskChart"></canvas> <!-- Graphique circulaire -->
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Durée Moyenne des Tâches</h5>
                        <canvas id="durationChart"></canvas> <!-- Graphique en barre -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Section des présences -->
        <div class="row mb-5">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Présences & Absences</h5>
                        <canvas id="attendanceChart"></canvas> <!-- Graphique à barres -->
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
        labels: ['Présents', 'Retards', 'Absents'],
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
