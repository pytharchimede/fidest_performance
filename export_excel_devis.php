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

$description = preg_replace('/[^a-zA-Z0-9]/', '_', $fiche['description']); // Remplace les caractères spéciaux par _
$description = str_replace(' ', '_', $description); // Remplace les espaces par _

// Créer le fichier Excel
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="Devis_' . $description . '_' . $ficheId . '.xls"');
header('Cache-Control: max-age=0');

echo "<table border='1'>";
echo "<tr>
        <th>" . mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Désignation', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Qte', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Prix Unitaire (FCFA)', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Prix Total (FCFA)', 'ISO-8859-1', 'UTF-8') . "</th>
      </tr>";

$totalGeneral = 0;
$i = 0;

foreach ($besoins as $besoin) {
    $prixTotal = $besoin['quantite'] * $besoin['prix_unitaire'];
    $totalGeneral += $prixTotal;
    $i++;
    echo "<tr>
            <td>" . mb_convert_encoding($i, 'ISO-8859-1', 'UTF-8') . "</td>
            <td>" . mb_convert_encoding(htmlspecialchars($besoin['objet']), 'ISO-8859-1', 'UTF-8') . "</td>
            <td>" . mb_convert_encoding(htmlspecialchars($besoin['quantite']), 'ISO-8859-1', 'UTF-8') . "</td>
            <td>" . ($besoin['prix_unitaire'] ? number_format($besoin['prix_unitaire'], 2) : mb_convert_encoding('A définir', 'ISO-8859-1', 'UTF-8')) . "</td>
            <td>" . ($prixTotal ? number_format($prixTotal, 2) : mb_convert_encoding('A définir', 'ISO-8859-1', 'UTF-8')) . "</td>
          </tr>";
}

// Ajouter une ligne pour le total général
echo "<tr>
        <td colspan='4'>" . mb_convert_encoding('Total Général', 'ISO-8859-1', 'UTF-8') . "</td>
        <td>" . number_format($totalGeneral, 2) . "</td>
      </tr>";

echo "</table>";
exit();
