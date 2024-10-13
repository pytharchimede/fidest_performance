<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
  header("Location: index.php");
  exit();
}

if ($_SESSION['acces_rh'] != 1) {
  header('Location: acces_refuse.php');
}

// Inclure la classe Personnel
require_once 'model/Personnel.php';
$personnelObj = new Personnel();

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id = intval($_GET['id']);
  $personnel = $personnelObj->getPersonnelById($id); // Assurez-vous que cette méthode existe et retourne les données du personnel

  //var_dump($personnel);
} else {
  header("Location: personnel.php"); // Rediriger si aucun ID n'est fourni
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Personnel</title>
  <!-- Intégration de Bootstrap et FontAwesome pour les icônes -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>

  <!-- Menu mobile-friendly -->
  <?php include 'menu.php'; ?>


  <div class="container mt-5">
    <div class="container mt-5">
      <h2>Modifier les informations de la personne</h2>
      <form id="form-modifier-personnel" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($personnel['id_personnel_tasks']); ?>">

        <label>Matricule:</label>
        <input type="text" name="matricule" required class="form-control" value="<?= htmlspecialchars($personnel['matricule_personnel_tasks']); ?>"><br>

        <label>Nom et Prénom(s):</label>
        <input type="text" name="nom" required class="form-control" value="<?= htmlspecialchars($personnel['nom_personnel_tasks']); ?>"><br>

        <label>Sexe (1 pour Homme, 2 pour Femme):</label>
        <input type="text" name="sexe" required class="form-control" value="<?= htmlspecialchars($personnel['sexe_personnel_tasks']); ?>"><br>

        <label>Téléphone:</label>
        <input type="text" name="telephone" class="form-control" required pattern="^0[0-9]{9}$" minlength="10" maxlength="10" title="Le numéro doit comporter exactement 10 chiffres et commencer par 0" value="<?= htmlspecialchars($personnel['tel_personnel_tasks']); ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" required class="form-control" value="<?= htmlspecialchars($personnel['email_personnel_tasks']); ?>"><br>

        <label>Salaire mensuel (en XOF):</label>
        <input type="number" name="salaire" required class="form-control" value="<?= htmlspecialchars($personnel['salaire_mensuel_personnel_tasks']); ?>"><br>

        <input type="submit" value="Modifier" class="btn btn-primary">
      </form>
    </div>

  </div>

  <!-- Intégration de jQuery et du fichier function_personnel.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="js/function_personnel.js"></script>
  <script src="js/style_script.js"></script>
</body>

</html>