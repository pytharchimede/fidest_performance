<?php
session_start();
require_once '../model/Task.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");    exit();
}

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

        // Rediriger vers la page de gestion des tâches après mise à jour
        header("Location: ../taches_en_attente.php");
        exit();
    }
} else {
    header("Location: ../taches_en_attente.php");
    exit();
}
