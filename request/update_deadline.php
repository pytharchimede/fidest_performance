<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../model/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = Database::getConnection();

    // Récupération des données soumises
    $task_id = $_POST['task_id'];
    $new_deadline = $_POST['date_report_propose'];

    // Validation des données
    if (!empty($task_id) && !empty($new_deadline)) {
        // Requête de mise à jour
        $sql = "UPDATE tasks SET deadline = :new_deadline, report_decide = 1  WHERE id = :task_id";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':new_deadline', $new_deadline);
            $stmt->bindParam(':task_id', $task_id);

            if ($stmt->execute()) {
                // Redirection après succès
                header("Location: ../taches_en_attente.php?success=report_updated");
                exit();
            } else {
                echo "Erreur lors de la mise à jour de la deadline.";
            }
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "Tous les champs sont requis.";
    }
} else {
    header("Location: ../taches_en_attente.php");
    exit();
}
?>
