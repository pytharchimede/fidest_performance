<?php
// Inclure les classes
require_once '../model/Database.php';
require_once '../model/FicheExpressionBesoin.php';
require_once '../model/BesoinExpression.php';
require_once '../model/BesoinExpressionFiles.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

// Connexion à la base de données
$pdo = Database::getConnection(); // Méthode getConnection à définir dans Database.php

// Instanciation des classes
$fiche = new FicheExpressionBesoin($pdo);
$besoin = new BesoinExpression($pdo);
$files = new BesoinExpressionFiles($pdo);

// Récupération des données du formulaire
$nom = $_POST['nom'];
$matricule = $_POST['matricule'];
$departement = $_POST['departement'];
$description = $_POST['description'];
$montant = $_POST['montant'];
$date = $_POST['date'];
$impact = $_POST['impact'];
$frequence = $_POST['frequence'];
$types = $_POST['type'];
$objets = $_POST['objet'];
$quantites = $_POST['quantite'];
$fournisseurs = $_POST['fournisseur'];
$nomFournisseurs = $_POST['nomFournisseur'];
$prixUnitaires = $_POST['prixUnitaire'];
$telephones = $_POST['telephone'];
$filesData = $_FILES['files'];

try {
    $pdo->beginTransaction();

    // Insertion de la fiche principale
    $expressionBesoinId = $fiche->insert($nom, $matricule, $departement, $description, $montant, $date, $impact, $frequence);

    // Insertion des besoins
    for ($i = 0; $i < count($types); $i++) {
        $fournisseur = isset($fournisseurs[$i]) ? 1 : 0;
        $nomFournisseur = $fournisseur ? $nomFournisseurs[$i] : null;
        $prixUnitaire = $fournisseur ? $prixUnitaires[$i] : null;
        $telephone = $fournisseur ? $telephones[$i] : null;

        $besoin->insert($expressionBesoinId, $types[$i], $objets[$i], $quantites[$i], $fournisseur, $nomFournisseur, $prixUnitaire, $telephone);
    }

    // Insertion des fichiers
    if (!empty($filesData['name'][0])) {
        for ($i = 0; $i < count($filesData['name']); $i++) {
            $fileName = $filesData['name'][$i];
            $fileTmpName = $filesData['tmp_name'][$i];
            $fileType = $filesData['type'][$i];
            $filePath = 'uploads/' . $fileName;

            if (move_uploaded_file($fileTmpName, $filePath)) {
                $files->insert($expressionBesoinId, $filePath, $fileType);
            }
        }
    }

    $pdo->commit();
    echo "Insertion réussie.";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
}
