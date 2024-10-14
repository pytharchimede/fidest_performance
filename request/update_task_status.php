<?php
session_start();
require_once '../model/Database.php';
require_once '../model/Task.php';
require_once '../model/TracabilitePerformance.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_code']) && isset($_POST['action'])) {
        $taskCode = $_POST['task_code'];
        $action = $_POST['action'];
        $status = '';

        $taskObj = new Task();

        switch ($action) {
            case 'complete':
                $status = 'Termine';
                break;
            case 'cancel':
                $status = 'Annulee';
                break;
            case 'reject':
                $status = 'Refusee';
                break;
            default:
                $status = 'En Attente'; // Valeur par défaut
                break;
        }

        $taskObj->updateTaskStatus($taskCode, $status);


        $consigneTask = $taskObj->getTaskByTaskCode($taskCode);


        // Ajout de la traçabilité
        $tracabilite = new TracabilitePerformance($pdo); // Instancier la classe Tracabilite
        $libelle = $_SESSION['nom_personnel_tasks'] . ' a défini le statut de la tâche N° ' . $taskCode . ', [ Consigne : ' . $consigneTask['description'] . ' ] à [ ' . $status . ' ]';
        $tracabilite->enregistrerAction($libelle); // Enregistrer l'action de traçabilité

        // Rediriger vers la page de gestion des tâches après mise à jour
        header("Location: ../taches_en_attente.php");
        exit();
    }
} else {
    header("Location: ../taches_en_attente.php");
    exit();
}
