<?php
session_start();
require_once '../model/Database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

// Connexion à la base de données
$pdo = Database::getConnection();

// Récupérer les détails de la recherche
$statut = $_GET['statut'] ?? null;
$matricule = $_GET['matricule'] ?? null;
$dateDebut = $_GET['date_debut'] ?? null;
$dateFin = $_GET['date_fin'] ?? null;

// Préparer la requête pour récupérer toutes les demandes selon les critères
$query = "SELECT * FROM demande_pret WHERE 1=1"; // '1=1' pour faciliter l'ajout de conditions

if ($statut) {
    $query .= " AND statut = :statut";
}
if ($matricule) {
    $query .= " AND matricule = :matricule";
}
if ($dateDebut) {
    $query .= " AND date_creat >= :dateDebut";
}
if ($dateFin) {
    $query .= " AND date_creat <= :dateFin";
}

$stmt = $pdo->prepare($query);

// Lier les paramètres si ils existent
if ($statut) {
    $stmt->bindParam(':statut', $statut);
}
if ($matricule) {
    $stmt->bindParam(':matricule', $matricule);
}
if ($dateDebut) {
    $stmt->bindParam(':dateDebut', $dateDebut);
}
if ($dateFin) {
    $stmt->bindParam(':dateFin', $dateFin);
}

$stmt->execute();
$demandeList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si aucune demande trouvée
if (empty($demandeList)) {
    echo "Aucune demande trouvée.";
    exit();
}

// Créer le fichier Excel
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="Liste_Demandes_Pret_' . date('d_m_Y') . '.xls"');
header('Cache-Control: max-age=0');

// Écrire les en-têtes du tableau
echo "<table border='1'>";
echo "<tr>
        <th>" . mb_convert_encoding('Matricule', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Désignation du prêt', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Montant demandé', 'ISO-8859-1', 'UTF-8') . "</th>
        <th>" . mb_convert_encoding('Date création', 'ISO-8859-1', 'UTF-8') . "</th>
      </tr>";

// Affichage des détails des demandes
foreach ($demandeList as $demandeDetails) {
    echo "<tr>
            <td>" . mb_convert_encoding($demandeDetails['matricule'], 'ISO-8859-1', 'UTF-8') . "</td>
            <td>" . mb_convert_encoding($demandeDetails['designation_pret'], 'ISO-8859-1', 'UTF-8') . "</td>
            <td>" . number_format($demandeDetails['montant_demande'], 0, ',', ' ') . ' FCFA' . "</td>
            <td>" . date("d/m/Y", strtotime($demandeDetails['date_creat'])) . "</td>
          </tr>";
}

// Fermer la balise du tableau
echo "</table>";
exit();
