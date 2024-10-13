<?php
session_start();
require_once '../model/Database.php';
require_once '../model/Personnel.php';
include('fpdf186/fpdf.php');

if ($_SESSION['acces_rh'] != 1) {
    header('Location: ../acces_refuse.php');
}

// Créer une classe personnalisée qui hérite de FPDF pour l'en-tête et le pied de page
class PDF extends FPDF
{
    // Méthode pour l'en-tête
    function Header()
    {
        // Logo
        $this->Image('../../img/logo_veritas.jpg', 150, 10, 30);
        // Police Arial gras 12
        $this->SetFont('Arial', 'B', 12);
        // Titre ou en-tête
        $this->Cell(0, 10, 'Rapport de Presence', 0, 1, 'C');
        // Saut de ligne
        $this->Ln(10);
    }

    // Méthode pour le pied de page
    function Footer()
    {
        // Positionner à 1,5 cm du bas
        $this->SetY(-22);

        // Image à gauche
        $this->Image('../../img/logo_veritas.jpg', 10, 265, 30);

        // Police Arial normale 7
        $this->SetFont('Arial', '', 7);

        // Informations de contact
        $this->Cell(0, 3.5, utf8_decode("FOURNITURES INDUSTRIELLES, DEPANNAGE ET TRAVAUX PUBLIQUES"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode('Au capital de 10 000 000 F CFA - Siège Social : Abidjan, Koumassi, Zone industrielle '), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode("01 BP 1642 Abidjan 01 - Téléphone : (+225) +225 27-21-36-27-27  -  Email : info@fidest.org"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode('RCCM : CI-ABJ-2017-B-20163  -  N° CC : 010274200088 '), 0, 1, 'C');

        // Image à droite
        $this->Image('../../img/logo_veritas.jpg', 172, 265, 30);

        // Numéro de page
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Créer une instance de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages(); // Compteur de pages
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Récupérer la date passée en $_GET
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Date par défaut : aujourd'hui

// Titre du document
$pdf->Cell(0, 10, mb_convert_encoding('Liste de Présences du ' . date('d/m/Y', strtotime($date)), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

try {
    $personnel = new Personnel();
    $pointages = $personnel->verifierPointageDuJourPourToutLeMonde($date);

    // Afficher les données
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(120, 10, 'Nom', 1);
    $pdf->Cell(50, 10, 'Statut', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $counter = 1;
    foreach ($pointages as $personnelId) {
        // Récupérer les détails du personnel
        $personnelDetails = $personnel->getPersonnelById($personnelId);

        // Vérifier le statut de présence
        $pointage = $personnel->verifierPointageDuJour($personnelId, $date);

        $nom = $personnelDetails['nom_personnel_tasks'] ?? '';
        $statut = ($pointage['present']) ? 'Présent' : 'Absent';

        $pdf->Cell(20, 10, $counter, 1);
        $pdf->Cell(120, 10, mb_convert_encoding($nom, 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Cell(50, 10, mb_convert_encoding($statut, 'ISO-8859-1', 'UTF-8'), 1);
        $pdf->Ln();
        $counter++;
    }
} catch (PDOException $e) {
    $pdf->SetFont('Arial', 'I', 12);
    $pdf->Cell(0, 10, mb_convert_encoding('Erreur de connexion à la base de données: ' . $e->getMessage(), 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
}

// Sauvegarder le PDF sur le serveur ou l'envoyer directement au navigateur
$pdf->Output('I', 'Liste_Presence_' . date('d_m_Y') . '.pdf');
