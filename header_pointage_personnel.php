<?php
// Inclure la classe Personnel pour lister le personnel
require_once 'model/Personnel.php';
$personnelObj = new Personnel();
$personnels = $personnelObj->listerPersonnel(); // Méthode qui retourne les données du personnel

// Traitement du pointage AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_personnel']) && isset($_POST['action'])) {
  $id_personnel = $_POST['id_personnel'];
  $date_pointage = date('Y-m-d');
  $heure_pointage = date('H:i:s');
  $action = $_POST['action'];
  
  // Vérifier si le pointage existe déjà pour aujourd'hui
  $pointageExistant = $personnelObj->verifierPointageDuJour($id_personnel, $date_pointage);
  
  if ($action === 'present') {
      if (!$pointageExistant) {
          // Enregistrer la présence (pointage) si elle n'a pas encore été faite aujourd'hui
          $personnelObj->enregistrerPresence($id_personnel, $date_pointage, $heure_pointage, 1);
          echo json_encode(['status' => 'success', 'message' => 'Pointage enregistré', 'id_personnel' => $id_personnel]);
      } else {
          echo json_encode(['status' => 'error', 'message' => 'Pointage déjà effectué pour aujourd\'hui']);
      }
  } elseif ($action === 'absent') {
      if (!$pointageExistant) {
          // Enregistrer l'absence (pointage) si elle n'a pas encore été faite aujourd'hui
          $personnelObj->enregistrerPresence($id_personnel, $date_pointage, '00:00:00', 0);
          echo json_encode(['status' => 'success', 'message' => 'Absence enregistrée', 'id_personnel' => $id_personnel]);
      } else {
          // Optionnel : mettre à jour l'entrée existante pour refléter l'absence si nécessaire
          $personnelObj->mettreAJourPresence($id_personnel, $date_pointage, '00:00:00', 0);
          echo json_encode(['status' => 'success', 'message' => 'Absence enregistrée', 'id_personnel' => $id_personnel]);
      }
  }
  exit;
}

//
$effectif_personnel = count($personnelObj->listerPersonnel());
$effectif_pointe_aujourdhui = count($personnelObj->verifierPointageDuJourPourToutLeMonde(date('Y-m-d')));


// Inclure la méthode pour vérifier les pointages existants pour aujourd'hui
$pointagesAujourdHui = $personnelObj->verifierPointageDuJourPourToutLeMonde(date('Y-m-d'));

?>