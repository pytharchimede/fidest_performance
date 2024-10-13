<?php
session_start(); // Assurez-vous que la session est démarrée
require_once '../model/DemandeAvance.php';
require_once '../model/Database.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    var_dump($_POST); // Ajoutez ceci pour vérifier les données envoyées

    // Récupérer les données du formulaire
    $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $fonction = isset($_POST['fonction']) ? $_POST['fonction'] : '';
    $service = isset($_POST['service']) ? $_POST['service'] : '';
    $motif = isset($_POST['motif']) ? $_POST['motif'] : '';
    $montant = isset($_POST['montant']) ? $_POST['montant'] : 0;
    $dateCreat = date('Y-m-d H:i:s'); // Date de création
    $securAjout = $_SESSION['id_personnel_tasks']; // Sécurité d'ajout (id de l'utilisateur)
    $statut = 'En Attente'; // Statut initial

    // Initialiser la classe DemandeAvance
    $demandeAvance = new DemandeAvance();

    // Appeler la méthode ajouterDemandeAvance
    $result = $demandeAvance->ajouterDemandeAvance(
        $nom,
        $matricule,
        $fonction,
        $service,
        $motif,
        $montant,
        $dateCreat,
        $statut
    );

    // Vérifier le résultat de l'insertion
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Demande d\'avance ajoutée avec succès !']);
        header('Location: ../dashboard.php');
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'ajout de la demande d\'avance.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
