<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
  header("Location: index.php");
  exit();
}

if ($_SESSION['acces_rh'] != 1) {
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
  <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
  <link href="plugins/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <!-- Menu mobile-friendly -->
  <?php include 'menu.php'; ?>


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
  <script src="js/style_script.js"></script>
</body>

</html>