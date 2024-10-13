<?php
session_start();
require_once '../model/Database.php';
include('fpdf186/fpdf.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
    header('Location: acces_refuse.php');
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

// Création de la classe PDF
class PDF extends FPDF
{
    // En-tête
    function Header()
    {
        $this->Image('../img/logo_fidest.jpg', 10, 10, 40);
        $this->Ln(35);
        $this->SetFont('Arial', 'BU', 22);
        $this->Cell(0, 10, mb_convert_encoding('LISTE DES DEMANDES DE PRET', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $this->Ln(10);
    }

    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Informations sur les demandes
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, mb_convert_encoding('Informations des Demandes', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);

// Entête du tableau
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(25, 10, mb_convert_encoding('Matricule', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(100, 10, mb_convert_encoding('Désignation du prêt', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(40, 10, mb_convert_encoding('Montant demandé', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(30, 10, mb_convert_encoding('Date création', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Ln();


// Réinitialiser la police à normale
$pdf->SetFont('Arial', '', 12);

// Variables pour calculer le total
$totalMontant = 0;

// Affichage des détails des demandes
foreach ($demandeList as $demandeDetails) {
    $pdf->Cell(25, 10, $demandeDetails['matricule'], 1);
    $pdf->Cell(100, 10, mb_convert_encoding($demandeDetails['designation_pret'], 'ISO-8859-1', 'UTF-8'), 1);
    $montantDemande = number_format($demandeDetails['montant_demande'], 0, ',', ' ') . ' FCFA';
    $pdf->Cell(40, 10, $montantDemande, 1);
    $pdf->Cell(30, 10, date("d/m/Y", strtotime($demandeDetails['date_creat'])), 1);
    $pdf->Ln();

    // Ajouter au total
    $totalMontant += $demandeDetails['montant_demande'];
}

// Affichage des totaux
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(125, 10, 'Total', 1);
$pdf->Cell(40, 10, number_format($totalMontant, 0, ',', ' ') . ' FCFA', 1);
$pdf->Cell(30, 10, '', 1); // Cell vide pour compléter le tableau
$pdf->Ln();

// Sauvegarder ou afficher le PDF
$pdf->Output('I', 'Liste_Demandes_Pret_' . date('d_m_Y') . '.pdf');

// Fermer la connexion à la base de données
unset($pdo);
