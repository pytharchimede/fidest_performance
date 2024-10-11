<?php
session_start();
require_once '../model/Database.php';
include('fpdf186/fpdf.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

// Connexion à la base de données
$pdo = Database::getConnection();

// Récupérer les détails de la demande
$idDemande = $_GET['id'] ?? null;
$req = "SELECT * FROM demande_avance_salaire WHERE id = :idDemande";
$stmt = $pdo->prepare($req);
$stmt->execute([':idDemande' => $idDemande]);
$demandeDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la demande n'existe pas
if (!$demandeDetails) {
    echo "Demande introuvable.";
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
        $this->Cell(0, 10, 'FICHE DE DEMANDE D\'AVANCE SUR SALAIRE', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pied de page
    function Footer()
    {
        $this->SetDrawColor(0, 0, 0);
        $this->Line(10, 272, 200, 272);
        $this->SetY(-22);
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3.5, utf8_decode("FOURNITURES INDUSTRIELLES, DEPANNAGE ET TRAVAUX PUBLIQUES - Au capital de 10 000 000 F CFA"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode("01 BP 1642 Abidjan 01 - Téléphone : (+225) +225 27-21-36-27-27 - Email : info@fidest.org"), 0, 1, 'C');
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Informations sur la demande d'avance
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Informations de la Demande', 0, 1, 'C');

$largeurCle = 120;
$largeurValeur = 100;
$pdf->Ln(10);

// Affichage des détails de la demande
$pdf->SetFont('Arial', '', 14);
$pdf->Cell($largeurValeur, 10, utf8_decode('Nom et Prénom(s):'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, strtoupper($demandeDetails['nom']), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, 'Matricule:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, $demandeDetails['matricule'], 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Fonction:'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, $demandeDetails['fonction'], 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Service:'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, $demandeDetails['service'], 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Montant demandé (FCFA):'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, number_format($demandeDetails['montant'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Motif:'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->MultiCell($largeurCle, 10, utf8_decode($demandeDetails['motif']), 0, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Ln(12);

$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(10, 10, 'Demandeur', 0, 0, 'L');
$pdf->Cell(160, 10, 'Responsable RH', 0, 0, 'C');
$pdf->Cell(10, 10, utf8_decode('Directeur Général'), 0, 1, 'R');

$pdf->Ln(50);

$pdf->SetFont('Arial', '', 12);
$date = new DateTime($demandeDetails['date_creat']);
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN);
$dateEnToutesLettres = $formatter->format($date);
$pdf->Cell(0, 10, utf8_decode('Fait à Abidjan, le ') . $dateEnToutesLettres, 0, 1, 'R');

$pdf->SetFont('Arial', '', 14);
$pdf->Output('I', 'Fiche_Demande_Avance_' . date('d_m_Y') . '.pdf');

// Fermer la connexion à la base de données
unset($pdo);
