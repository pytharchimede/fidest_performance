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
    $matricule = isset($_POST['matricule']) ? $_POST['matricule'] : '';
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
    $tel = isset($_POST['tel']) ? $_POST['tel'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $salaire = isset($_POST['salaire']) ? $_POST['salaire'] : 0;

    // Initialiser la classe Personnel
    $personnel = new Personnel();

    // Appeler la méthode ajouterPersonnel
    $result = $personnel->ajouterPersonnelTask(
        $matricule, 
        $nom, 
        $sexe, 
        $tel, 
        $email,
        $salaire
    );

    // Vérifier le résultat de l'insertion
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Personnel ajouté avec succès !']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Échec de l\'ajout du personnel.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
