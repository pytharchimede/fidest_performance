<?php
// Connexion à la base de données et démarrage de la session
session_start();
require_once '../model/Database.php';
require_once '../model/FicheExpressionBesoin.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
    header('Location: ../acces_refuse.php');
}

$idFiche = $_GET["id"];

$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

$fiche = new FicheExpressionBesoin($pdo);



// Pour accepter une fiche
$resultatAccepte = $fiche->refuserFicheExpressionBesoin($idFiche);


header('Location: ../liste_feb.php');
