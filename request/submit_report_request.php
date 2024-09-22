<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../model/Database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pdo = Database::getConnection();

    $task_id = $_POST['task_id'];
    $date_report_propose = $_POST['date_report_propose'];
    $motif_report = $_POST['motif_report'];

    // Validation et sécurisation des données
    $task_id = htmlspecialchars($task_id);
    $date_report_propose = htmlspecialchars($date_report_propose);
    $motif_report = htmlspecialchars($motif_report);

    // Mise à jour de la tâche dans la base de données avec PDO
    $query = "UPDATE tasks SET report_demande = 1, date_report_propose = :date_report_propose, motif_report = :motif_report WHERE id = :task_id";
    $stmt = $pdo->prepare($query);

    // Liaison des paramètres avec bindParam ou en passant les paramètres directement dans execute
    $stmt->bindParam(':date_report_propose', $date_report_propose);
    $stmt->bindParam(':motif_report', $motif_report);
    $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirection ou message de succès
        header('Location: ../taches_en_attente.php?message=report_success');
        exit();
    } else {
        // Gestion de l'erreur
        echo "Erreur lors de la demande de report";
    }
}
?>
