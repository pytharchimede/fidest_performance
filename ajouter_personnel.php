<?php 
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

if($_SESSION['acces_rh']!=1){
  header('Location: acces_refuse.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Personnel</title>
    <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

  <!-- Menu mobile-friendly -->
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

  <div class="container mt-5">
    <!-- Section Profil avec photo, nom et poste -->
    <!-- <div class="profile-section mb-4 p-3 bg-white rounded shadow-sm d-flex align-items-center">
      <img src="https://via.placeholder.com/60" alt="Photo de profil" class="rounded-circle">
      <div class="profile-info ml-3">
        <h5 class="text-primary">Jean Dupont</h5>
        <p class="text-muted">Informaticien</p>
      </div>
    </div> -->

    <div class="container mt-5">
        <h2>Ajouter une nouvelle personne</h2>
        <form id="form-ajouter-personnel" enctype="multipart/form-data">
            <label>Matricule:</label>
            <input type="text" name="matricule" required class="form-control"><br>
            
            <label>Nom et Prénom(s):</label>
            <input type="text" name="nom" required class="form-control"><br>
            
            <label>Sexe (1 pour Homme, 2 pour Femme):</label>
            <input type="text" name="sexe" required class="form-control"><br>
                        
            <label>Téléphone:</label>
            <input type="text" name="telephone" class="form-control" required pattern="^0[0-9]{9}$" minlength="10" maxlength="10" title="Le numéro doit comporter exactement 10 chiffres et commencer par 0"><br>

            <label>Email:</label>
            <input type="email" name="email" required class="form-control"><br>

            <label>Salaire mensuel (en XOF):</label>
            <input type="number" name="salaire" required class="form-control"><br>
                        
            <input type="submit" value="Ajouter" class="btn btn-primary">
        </form>
    </div>

    </div>


    <!-- Intégration de jQuery et du fichier function_personnel.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="js/function_personnel.js"></script>
</body>
</html>
