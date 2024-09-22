<?php
session_start();
require_once '../model/Database.php';
include('fpdf186/fpdf.php');

// Vérifier si l'utilisateur est connecté et a les droits nécessaires
if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['acces_rh'] != 1) {
    header('Location: ../acces_refuse.php');
    exit();
}

// Connexion à la base de données
$pdo = Database::getConnection();

// Récupérer le mois et l'année courant
$moisCourant = date('m');
$anneeCourante = date('Y');
$dateDebut = "$anneeCourante-$moisCourant-01";
$dateFin = date('Y-m-t'); // Dernier jour du mois courant

// Récupérer tous les pointages
$req = "SELECT p.nom_personnel_tasks, pt.date_pointage, pt.present 
        FROM personnel_tasks AS p
        LEFT JOIN pointage_personnel AS pt ON p.id_personnel_tasks = pt.personnel_tasks_id 
        WHERE pt.date_pointage BETWEEN :dateDebut AND :dateFin";
$records = $pdo->prepare($req);
$records->execute([':dateDebut' => $dateDebut, ':dateFin' => $dateFin]);

// Créer un tableau pour stocker les données
$pointages = [];
while ($row = $records->fetch()) {
    $pointages[$row['nom_personnel_tasks']][$row['date_pointage']] = $row['present'] ? 'Présent' : 'Absent';
}

// Création de la classe PDF
class PDF extends FPDF
{
    // En-tête
    function Header()
    {
        //$this->Image('../../img/logo_veritas.jpg', 150, 10, 30);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Pointage du Personnel', 0, 1, 'C');
        $this->Ln(5);
    }

    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }
}

// Instanciation de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L'); // Orientation paysage
$pdf->SetMargins(10, 10, 10); // Marges gauche, haut et droite
$pdf->SetFont('Arial', '', 9); // Réduction de la taille de police

// Titre du mois et de l'année
$pdf->Cell(0, 10, 'Pointage du Personnel - ' . htmlspecialchars($moisCourant . '/' . $anneeCourante), 0, 1, 'C');
$pdf->Ln(5);

// En-tête du tableau
$pdf->SetFillColor(0, 123, 255); // Couleur de fond pour les en-têtes
$pdf->SetTextColor(255); // Couleur du texte
$pdf->Cell(10, 10, '#', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Nom Personnel', 1, 0, 'C', true);

// Date headers
foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date) {
    $pdf->Cell(8, 10, $date->format("d"), 1, 0, 'C', true); // Réduction à 8 mm
}
$pdf->Cell(8, 10, 'TJT', 1, 1, 'L', true); // Réduction à 25 mm

// Remise à zéro des couleurs de texte
$pdf->SetTextColor(0);
$i = 0;

// Affichage des données
foreach ($pointages as $nom => $statuts) {
    $i++;
    $pdf->Cell(10, 10, $i, 1);
    $pdf->SetFont('Arial', '', 6); // Réduction de la taille de police
    $pdf->Cell(30, 10, htmlspecialchars($nom), 1);
    $pdf->SetFont('Arial', '', 9); // Réduction de la taille de police

    $totalJoursTravailles = 0;
    foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date) {
        $dateFormat = $date->format("Y-m-d");
        $statut = isset($statuts[$dateFormat]) ? $statuts[$dateFormat] : 'Absent';
        $couleur = ($statut === 'Présent') ? [40, 167, 69] : [220, 53, 69]; // Vert pour présent, rouge pour absent
        $pdf->SetFillColor(...$couleur);
        $pdf->Cell(8, 10, '', 1, 0, 'C', true); // Case colorée sans texte
        if ($statut === 'Présent') $totalJoursTravailles++;
    }
    $pdf->Cell(8, 10, htmlspecialchars($totalJoursTravailles), 1);
    $pdf->Ln();
}

// Sauvegarder ou afficher le PDF
$pdf->Output('I', 'Pointage_Personnel_' . date('d_m_Y') . '.pdf');

// Nettoyer la connexion PDO
unset($pdo);
?>
