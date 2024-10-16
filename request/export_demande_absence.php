<?php
session_start();
require_once '../model/Database.php';
include('fpdf186/fpdf.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['acces_absence'] != 1) {
    header('Location: ../acces_refuse.php');
}

// Connexion à la base de données
$pdo = Database::getConnection();

// Récupérer les détails de la demande d'absence
$idDemande = $_GET['id'] ?? null;
$req = "SELECT * FROM demande_absence WHERE id = :idDemande";
$stmt = $pdo->prepare($req);
$stmt->execute([':idDemande' => $idDemande]);
$demandeDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la demande n'existe pas
if (!$demandeDetails) {
    echo "Demande introuvable.";
    exit();
}

// Création de la classe PDF pour la fiche d'autorisation d'absence
class PDF extends FPDF
{
    // En-tête
    function Header()
    {
        $this->Image('../img/logo_fidest.jpg', 10, 10, 40);
        $this->Ln(35);
        $this->SetFont('Arial', 'BU', 22);
        $this->Cell(0, 10, mb_convert_encoding('FICHE D\'AUTORISATION D\'ABSENCE', 'ISO-8859-1'), 0, 1, 'C');
        $this->Ln(10);
    }

    // Pied de page
    function Footer()
    {
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, 272, 200, 272);
        $this->SetY(-22);
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3.5, mb_convert_encoding("FOURNITURES INDUSTRIELLES, DEPANNAGE ET TRAVAUX PUBLIQUES - Au capital de 10 000 000 F CFA", 'ISO-8859-1'), 0, 1, 'C');
        $this->Cell(0, 3.5, mb_convert_encoding("01 BP 1642 Abidjan 01 - Téléphone : (+225) +225 27-21-36-27-27 - Email : info@fidest.org", 'ISO-8859-1'), 0, 1, 'C');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Informations sur la demande d'absence
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, mb_convert_encoding('Informations de la Demande d\'Absence', 'ISO-8859-1'), 0, 1, 'C');

$largeurCle = 120;
$largeurValeur = 100;
$pdf->Ln(10);

// Affichage des détails de la demande
$pdf->SetFont('Arial', '', 14);
$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Nom et Prénom(s):', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding(strtoupper($demandeDetails['nom']), 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Matricule:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['matricule'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Fonction:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['fonction'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Service:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['service'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Motif:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->MultiCell($largeurCle, 10, mb_convert_encoding($demandeDetails['motif'], 'ISO-8859-1'), 0, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Date de départ:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['date_depart'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Date de retour:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['date_retour'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, mb_convert_encoding('Nombre de jours:', 'ISO-8859-1'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, mb_convert_encoding($demandeDetails['nombre_jours'], 'ISO-8859-1'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Ln(12);

$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(10, 10, mb_convert_encoding('Demandeur', 'ISO-8859-1'), 0, 0, 'L');
$pdf->Cell(160, 10, mb_convert_encoding('Responsable RH', 'ISO-8859-1'), 0, 0, 'C');
$pdf->Cell(10, 10, mb_convert_encoding('Directeur Général', 'ISO-8859-1'), 0, 1, 'R');

$pdf->Ln(50);

// Format de la date en toutes lettres
$pdf->SetFont('Arial', '', 12);
$date = new DateTime($demandeDetails['date_creat']);
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN);
$dateEnToutesLettres = $formatter->format($date);
$pdf->Cell(0, 10, mb_convert_encoding('Fait à Abidjan, le ', 'ISO-8859-1') . $dateEnToutesLettres, 0, 1, 'R');

$pdf->SetFont('Arial', '', 14);
$pdf->Output('I', 'Fiche_Demande_Absence_' . date('d_m_Y') . '.pdf');

// Fermer la connexion à la base de données
unset($pdo);
