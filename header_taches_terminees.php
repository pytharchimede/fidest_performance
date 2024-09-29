<?php 
// Inclure les modèles nécessaires
require_once 'model/Task.php';
require_once 'model/Personnel.php';

// Instanciation des objets
$taskObj = new Task();
$personnelObj = new Personnel();
$personnelId = $_SESSION['id_personnel_tasks'];

// Initialisation des variables
$taches = [];

// Récupération des dates de début et de fin depuis la requête GET
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;

// Vérification de la présence des dates pour filtrer les tâches
if ($date_debut && $date_fin) {
    // echo 'Date de Début : ' . htmlspecialchars($date_debut) . '<br>';
    // echo 'Date de Fin : ' . htmlspecialchars($date_fin) . '<br>';
    
    // Récupérer les tâches par date
    if ($_SESSION['role'] == 'superviseur') {
        $taches = $taskObj->getTasksByDateRange($date_debut, $date_fin);
    } else {
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByMatriculeAndDateRange($matricule, $date_debut, $date_fin);
    }
} else {
    // Récupérer les tâches par défaut (Termine)
    if ($_SESSION['role'] == 'superviseur') {
        $taches = $taskObj->getTasksByStatus('Termine');
    } else {
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'Termine');
    }
}

// Compter le nombre de tâches récupérées
$nbTaches = count($taches);


// echo 'Date de Début : ' . htmlspecialchars($date_debut) . '<br>';
// echo 'Date de Fin : ' . htmlspecialchars($date_fin) . '<br>';

?>