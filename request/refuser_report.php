<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../model/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {

    $pdo = Database::getConnection();

    $task_id = intval($_POST['task_id']); // Sécurisation de la variable


    try {
        // Préparer la requête pour mettre à jour les champs report_decide et report_refuse
        $query = "UPDATE tasks SET report_decide = 1, report_refuse = 1 WHERE id = :task_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Rediriger l'utilisateur avec un message de succès
            $_SESSION['message'] = "Le report de la tâche a été refusé avec succès.";
            header("Location: ../demandes_report.php");
        } else {
            // Rediriger avec un message d'erreur si la requête échoue
            $_SESSION['error'] = "Erreur lors de la mise à jour de la demande.";
            header("Location: ../demandes_report.php");
        }
    } catch (PDOException $e) {
        // Gestion des erreurs
        $_SESSION['error'] = "Erreur: " . $e->getMessage();
        header("Location: ../demandes_report.php");
    }
} else {
    // Rediriger si l'accès est incorrect
    $_SESSION['error'] = "Accès non autorisé.";
    header("Location: ../demandes_report.php");
}
?>
