<?php
session_start();
require_once '../model/DemandePret.php';
require_once '../model/Database.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer les données du formulaire
    $designation_pret = isset($_POST['designation_pret']) ? $_POST['designation_pret'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : ''; // Nouveau champ matricule
    $montant_demande = isset($_POST['montant_demande']) ? $_POST['montant_demande'] : 0;
    $montant_recouvrement = isset($_POST['montant_recouvrement']) ? $_POST['montant_recouvrement'] : 0;
    $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
    $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';
    $dateCreat = date('Y-m-d H:i:s');
    $securAjout = $_SESSION['id_personnel_tasks'];
    $statut = 'En Attente';

    // Initialiser la classe DemandePret
    $demandePret = new DemandePret();

    // Appeler la méthode ajouterDemandePret
    $result = $demandePret->ajouterDemandePret(
        $designation_pret,
        $nom,
        $matricule, // Passer le matricule ici
        $montant_demande,
        $montant_recouvrement,
        $date_debut,
        $date_fin,
        $dateCreat,
        $securAjout,
        $statut
    );

    // Vérifier le résultat de l'insertion
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Demande de prêt ajoutée avec succès !']);
        header('Location: ../dashboard.php');
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'ajout de la demande de prêt.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
