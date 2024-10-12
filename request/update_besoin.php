<?php
// Inclure les classes
require_once '../model/Database.php';
require_once '../model/FicheExpressionBesoin.php';
require_once '../model/BesoinExpression.php';

// Connexion à la base de données
$pdo = Database::getConnection(); // Assurez-vous que cette méthode est définie dans Database.php

// Instanciation de la classe
$besoin = new BesoinExpression($pdo);

// Récupération des données du formulaire
$id = $_POST['id'];
$objet = $_POST['objet'];
$quantite = $_POST['quantite'];
$prixUnitaire = $_POST['prix_unitaire'];
$fournisseur = $_POST['fournisseur'] ?? 0; // Valeur par défaut si non spécifié
$nomFournisseur = $_POST['nom_fournisseur'] ?? null;
$telephone = $_POST['telephone'] ?? null;

try {
    $besoin->update($id, $objet, $quantite, $prixUnitaire, $fournisseur, $nomFournisseur, $telephone);

    // Réponse réussie
    $response['status'] = 'success';
    $response['message'] = 'Mise à jour réussie.';
} catch (PDOException $e) {
    // Réponse d'erreur
    $response['status'] = 'error';
    $response['message'] = 'Erreur : ' . $e->getMessage();
}

// Définir l'en-tête de réponse pour le JSON
header('Content-Type: application/json');

// Retourner la réponse JSON
echo json_encode($response);
