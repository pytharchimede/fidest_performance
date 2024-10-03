<?php
session_start();
// Inclure la connexion à la base de données
require_once '../model/Database.php';
require_once '../model/Personnel.php'; // Inclure la classe Personnel
require_once '../model/Task.php'; // Inclure la classe Personnel
require_once '../../OrangeSMS.php';
require_once '../model/TracabilitePerformance.php'; // Inclure la classe TracabilitePerformance


if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");    exit();
}

if($_SESSION['role']!='superviseur'){
    header('Location: ../acces_refuse.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::getConnection();

        // Récupérer les données du formulaire
        $taskDescription = $_POST['taskDescription'];
        $assignedTo = $_POST['assignedTo'];
        $deadline = $_POST['deadline'];
        $duree = $_POST['duree'];
        $matricule_assignateur = $_POST['matricule_assignateur'];
        $projet = $_POST['projet'];
        $id_tache = $_POST['id_tache'];

        // Instancier la classe Task
        $task = new Task();

        // Mettre à jour la tâche
        if ($task->update($taskDescription, $assignedTo, $deadline, $duree, $matricule_assignateur, $projet, $id_tache)) {
            echo 'Tâche mise à jour avec succès !';
        } else {
            echo 'Erreur lors de la mise à jour de la tâche.';
        }

    } catch (Exception $e) {
        error_log('Erreur : ' . $e->getMessage());
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    echo 'Méthode HTTP non autorisée';
}


?>