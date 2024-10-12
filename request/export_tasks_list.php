<?php
session_start();
require_once '../model/Task.php';
require_once '../model/Personnel.php';

require_once 'fpdf/fpdf.php';

$taskObj = new Task();
$personnelObj = new Personnel();

// Vérifier si les dates de début et de fin sont définies
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : null;
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : null;

// Convertir les dates en français
$date_debut_fr = $date_debut ? date('d/m/Y', strtotime($date_debut)) : null;
$date_fin_fr = $date_fin ? date('d/m/Y', strtotime($date_fin)) : null;

// Initialiser un tableau pour les tâches
$taches = [];

// Récupération des tâches selon les critères
if (is_null($date_debut) && is_null($date_fin)) {
    if ($_SESSION['role'] == 'superviseur') {
        $taches = $taskObj->getTasksByStatus('En Attente');
    } else {
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByMatriculeAndStatus($matricule, 'En Attente');
    }
} else {
    if ($_SESSION['role'] == 'superviseur') {
        $taches = $taskObj->getTasksByDateRange($date_debut, $date_fin);
    } else {
        $matricule = $_SESSION['matricule_personnel_tasks'];
        $taches = $taskObj->getTasksByEnAttenteMatriculeAndDateRange($matricule, $date_debut, $date_fin);
    }
}

$personnelDetails = $personnelObj->getPersonnelByMatricule($matricule);

// Vérifier si des tâches ont été récupérées
if (empty($taches)) {
    die(utf8_decode('Aucune tâche trouvée pour les critères spécifiés.'));
}

// Créer une classe personnalisée pour gérer l'en-tête et le pied de page
class PDF extends FPDF
{
    // Méthode pour l'en-tête
    function Header()
    {

        // Logo
        $this->Image('../../img/logo_veritas.jpg', 150, 10, 30);
        // Logo
        $this->Image('https://fidest.ci/logi/img/logo_jpg.jpg', 10, 10, 40); // Ajuster la taille si nécessaire
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(29, 43, 87); // Couleur bleue
        $this->Cell(0, 10, utf8_decode('FIDEST'), 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'I', 12);
        $this->Cell(0, 10, utf8_decode("Liste des Tâches de (En attente d'exécution) "), 0, 1, 'C');
        $this->Ln(5);
    }

    // Méthode pour le pied de page
    function Footer()
    {
        // Positionnement à 1,5 cm du bas
        $this->SetY(-30);

        // Informations de contact
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(29, 43, 87); // Couleur bleue
        $this->Cell(0, 3.5, utf8_decode("FOURNITURES INDUSTRIELLES, DEPANNAGE ET TRAVAUX PUBLIQUES"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode('Au capital de 10 000 000 F CFA - Siège Social : Abidjan, Koumassi, Zone industrielle'), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode("01 BP 1642 Abidjan 01 - Téléphone : (+225) 27-21-36-27-27  - Email : info@fidest.org"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode('RCCM : CI-ABJ-2017-B-20163 - N° CC : 010274200088'), 0, 1, 'C');

        // Numéro de page
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Créer une instance du PDF
$pdf = new PDF();
$pdf->AliasNbPages(); // Compteur de pages
$pdf->AddPage();



// Ajouter l'intervalle de dates
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(29, 43, 87); // Couleur bleue
if ($date_debut_fr && $date_fin_fr) {
    $pdf->Cell(0, 10, utf8_decode("Tâches assignées du $date_debut_fr au $date_fin_fr"), 0, 1, 'C');
} else {
    $pdf->Cell(0, 10, utf8_decode("Aucune intervalle de dates spécifiée."), 0, 1, 'C');
}
$pdf->Ln(5); // Saut de ligne pour espacer

// Ligne de séparation
$pdf->SetDrawColor(29, 43, 87); // Couleur de la ligne
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Ligne horizontale
$pdf->Ln(5); // Saut de ligne supplémentaire

$pdf->Cell(0, 10, utf8_decode("Personnel : " . strtoupper($personnelDetails['nom_personnel_tasks'])), 0, 1, 'C');


$pdf->Ln(5); // Saut de ligne supplémentaire


// En-têtes de colonne
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Code', 1);
$pdf->Cell(70, 10, 'Description', 1);
$pdf->Cell(45, 10, 'Date Limite', 1);
$pdf->Cell(25, 10, utf8_decode('Durée'), 1);
$pdf->Cell(25, 10, 'Statut', 1);
$pdf->Ln();

// Données des tâches
$pdf->SetFont('Arial', '', 12);

// Marges de bas de page
$footerHeight = 30; // Hauteur estimée pour éviter le chevauchement du tableau avec le pied de page

// Données des tâches
$pdf->SetFont('Arial', '', 12);

foreach ($taches as $tache) {
    // Description de la tâche
    $description = mb_convert_encoding($tache['description'], 'UTF-8', 'auto');

    // Largeurs des cellules
    $descCellWidth = 70;
    $lineHeight = 6; // Hauteur des lignes pour l'affichage
    $cellHeight = 6; // Hauteur par défaut des cellules sans MultiCell

    // Calcul du nombre de lignes nécessaires pour la description
    $descriptionWidth = $pdf->GetStringWidth($description);
    $nb_lignes_desc = ceil($descriptionWidth / $descCellWidth);
    $cellHeightDescription = $nb_lignes_desc * $lineHeight; // Hauteur ajustée pour la description

    // Définir la hauteur maximale de la ligne (basée sur la description ou la cellule la plus grande)
    $maxCellHeight = max($cellHeightDescription, $cellHeight);

    // Obtenir la hauteur de la page
    $pageHeight = $pdf->h; // Utiliser $pdf->h pour obtenir la hauteur de la page dans FPDF

    // Vérifier si un saut de page est nécessaire avant d'ajouter une nouvelle ligne
    if ($pdf->GetY() + $maxCellHeight > $pageHeight - $footerHeight) {
        $pdf->AddPage();
    }

    // Affichage des colonnes avec la hauteur dynamique

    // Code de la tâche
    $pdf->Cell(30, $maxCellHeight, utf8_decode($tache['task_code']), 1, 0);

    // Sauvegarder la position actuelle pour la description
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Description de la tâche avec MultiCell
    $pdf->MultiCell($descCellWidth, $lineHeight, utf8_decode($description), 1, 'L');

    // Repositionner pour continuer sur la même ligne
    $pdf->SetXY($x + $descCellWidth, $y);

    // Deadline
    $pdf->Cell(45, $maxCellHeight, utf8_decode($tache['deadline']), 1, 0);

    // Durée
    $pdf->Cell(25, $maxCellHeight, utf8_decode($tache['duree']), 1, 0);

    // Statut de report
    $pdf->Cell(25, $maxCellHeight, $tache['report_decide'] == 1 ? utf8_decode('Reporté') : utf8_decode('En Attente'), 1, 1);
}



// Sortie du PDF
$pdf->Output();
exit;
