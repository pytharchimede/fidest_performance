<?php
session_start();

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: index.php");
    exit();
}

require_once 'model/Database.php';
require_once 'model/FicheExpressionBesoin.php';
require_once 'model/BesoinExpression.php';

// Instanciations
$databaseObj = new Database();
$pdo = $databaseObj->getConnection();
$ficheExpression = new FicheExpressionBesoin($pdo);
$besoinObj = new BesoinExpression($pdo);

// Récupérer l'ID de la fiche à partir de la requête GET
$ficheId = $_GET['id'];

// Récupérer la fiche et les besoins
$fiche = $ficheExpression->obtenirFicheParId($ficheId);
$besoins = $besoinObj->getBesoinsByFicheId($ficheId);

// Créer le fichier Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="besoins.xls"');
header('Cache-Control: max-age=0');

echo "<table border='1'>";
echo "<tr>
        <th>N°</th>
        <th>Désignation</th>
        <th>Qte</th>
        <th>Prix Unitaire (FCFA)</th>
        <th>Prix Total (FCFA)</th>
      </tr>";

$totalGeneral = 0;
$i = 0;

foreach ($besoins as $besoin) {
    $prixTotal = $besoin['quantite'] * $besoin['prix_unitaire'];
    $totalGeneral += $prixTotal;
    $i++;
    echo "<tr>
            <td>$i</td>
            <td>" . htmlspecialchars($besoin['objet']) . "</td>
            <td>" . htmlspecialchars($besoin['quantite']) . "</td>
            <td>" . ($besoin['prix_unitaire'] ? number_format($besoin['prix_unitaire'], 2) : 'A définir') . "</td>
            <td>" . ($prixTotal ? number_format($prixTotal, 2) : 'A définir') . "</td>
          </tr>";
}

// Ajouter une ligne pour le total général
echo "<tr>
        <td colspan='4'>Total Général</td>
        <td>" . number_format($totalGeneral, 2) . "</td>
      </tr>";

echo "</table>";
exit();
