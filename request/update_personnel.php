<?php
require_once '../model/Personnel.php';
require_once '../model/Database.php';


if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");    exit();
}

if($_SESSION['acces_rh']!=1){
    header('Location: ../acces_refuse.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $id = isset($_POST['id']) ? $_POST['id'] : ''; // Assurez-vous que l'ID du personnel est envoyé
    $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $salaire = isset($_POST['salaire']) ? $_POST['salaire'] : '';

    // Initialiser la classe Personnel
    $personnel = new Personnel();

    // Appeler la méthode pour mettre à jour le personnel
    $result = $personnel->mettreAJourPersonnel(
        $id, 
        $matricule, 
        $nom, 
        $sexe, 
        $telephone, 
        $email, 
        $salaire
    );

    // Vérifier le résultat de la mise à jour
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Les informations du personnel ont été mises à jour avec succès !']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour des informations du personnel.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
