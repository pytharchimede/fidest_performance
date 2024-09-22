<?php

// Inclure les classes Task et Personnel
require_once 'model/Task.php';
require_once 'model/Personnel.php';
require_once 'model/Helper.php';


// Récupérer les informations de salaire pour un personnel spécifique
$personnelId = $_SESSION['id_personnel_tasks']; // Remplacer par l'ID du personnel connecté
$personnelObj = new Personnel();
$salaireInfo = $personnelObj->getSalaireByPersonnelId($personnelId);
$avanceInfo = $personnelObj->getAvanceByPersonnelId($personnelId);
$presenceInfo = $personnelObj->getPresenceByPersonnelId($personnelId);

$salaireMensuel = $salaireInfo['salaire_mensuel_personnel_tasks'];
$avanceDeductible = $avanceInfo['avance_deductible'];
$joursTravailles = $presenceInfo['jours_travailles'];

//Récupérer les tâches en attente 
$taskObj = new Task();


// Récupérer le rôle du personnel
$role = $personnelObj->getRoleById($personnelId);

// var_dump($role);

$matricule = $_SESSION['matricule_personnel_tasks'];

if ($role == 'superviseur') {
  $tachesEnAttente = $taskObj->getTasksByStatus('En Attente');
  $tachesOk = $taskObj->getTasksByStatus('Termine');
  $tachesRefusees = $taskObj->getTasksByStatus('Refusee');
  $tachesAnnulees = $taskObj->getTasksByStatus('Annulee');
} else {
  $tachesEnAttente = $taskObj->getTasksByMatriculeAndStatus($matricule, 'En Attente');
  $tachesOk = $taskObj->getTasksByMatriculeAndStatus($matricule, 'Termine');
  $tachesRefusees = $taskObj->getTasksByMatriculeAndStatus($matricule, 'Refusee');
  $tachesAnnulees = $taskObj->getTasksByMatriculeAndStatus($matricule, 'Annulee');
}

$nbTachesEnAttente = count($tachesEnAttente);
$nbTachesOk = count($tachesOk);
$nbTachesRefusees = count($tachesRefusees);
$nbTachesAnnulees = count($tachesAnnulees);

$salaireAcquis = ($salaireMensuel/20)*$joursTravailles-$avanceDeductible;

//Nombre total de taches 
$nbTachesTotal = count($taskObj->getTasksByMatricule($matricule));

//Nombre de taches expirées
$nbTachesExpired = count($taskObj->getTaskExpiredByMatricule($matricule));


// Créer une instance de Helper
$helper = new Helper();

// Calculer le score en fonction des tâches en attente et des tâches terminées
$score = $helper->calculerScore($nbTachesOk, $nbTachesTotal);

// Déterminer la couleur du score et de la barre de progression
if ($score >= 80) {
  $scoreClass = 'text-success';
  $progressBarClass = 'bg-success';
} elseif ($score >= 50) {
  $scoreClass = 'text-warning';
  $progressBarClass = 'bg-warning';
} else {
  $scoreClass = 'text-danger';
  $progressBarClass = 'bg-danger';
}

// Récupérer et trier les personnels
$allPersonnel = $personnelObj->getAllPersonnelWithScores();
usort($allPersonnel, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// Trouver le classement du personnel connecté
$ranking = array_search($personnelId, array_column($allPersonnel, 'id_personnel_tasks')) + 1;
?>