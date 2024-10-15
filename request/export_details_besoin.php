<?php
session_start();

require_once '../model/Database.php';
require_once '../model/FicheExpressionBesoin.php';
require_once '../model/Service.php';
require_once '../model/Personnel.php';
require_once '../model/BesoinExpression.php';
require_once '../model/BesoinExpressionFiles.php';
require_once '../model/Helper.php';
require_once 'fpdf186/fpdf.php';

// Instanciations
$databaseObj = new Database();
$pdo = $databaseObj->getConnection();

$helperObj = new Helper();
$serviceObj = new Service();
$personnelObj = new Personnel();
$ficheExpression = new FicheExpressionBesoin($pdo);
$besoinObj = new BesoinExpression($pdo);
$expressionBesoinFiles = new BesoinExpressionFiles($pdo);

// Récupérer l'ID de la fiche à partir de la requête GET
$ficheId = $_GET['id'] ?? null; // Utilise null si 'id' n'est pas défini
// Vérifier si l'ID est valide
if ($ficheId === null) {
    die('Erreur : ID de fiche non spécifié.');
}

$fiche = $ficheExpression->obtenirFicheParId($ficheId);
if (!$fiche) {
    die('Erreur : Fiche non trouvée pour l\'ID donné.');
}

$demandeur = $personnelObj->getPersonnelByMatricule($fiche['matricule']);
if (!$demandeur) {
    die('Erreur : Demandeur non trouvé pour le matricule spécifié.');
}

$service = $serviceObj->obtenirServiceParId($fiche['departement']);
if (!$service) {
    die('Erreur : Service non trouvé pour le département spécifié.');
}

$besoins = $besoinObj->getBesoinsByFicheId($ficheId);
if (!is_array($besoins)) {
    $besoins = []; // Définit un tableau vide par défaut
}

$files = $expressionBesoinFiles->getFilesByFicheId($ficheId);
if (!is_array($files)) {
    $files = []; // Définit un tableau vide par défaut
}




// Classe PDF personnalisée
// Classe PDF personnalisée
class PDF extends FPDF
{
    function Header()
    {
        $this->Image('https://fidest.ci/logi/img/logo_jpg.jpg', 10, 10, 40);
        $this->SetFont('Arial', 'BU', 20);
        $this->Ln(17);
        $this->Cell(0, 10, 'FICHE D\'EXPRESSION DE BESOIN', 0, 1, 'C'); // Retrait de utf8_decode
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Créer une instance du PDF
$pdf = new PDF();
$pdf->AddPage();

$pdf->Ln(13);

$space = 8; // Définir la hauteur de la cellule
$width = 50;

// Libellé "Demandeur"
$pdf->SetFont('Arial', '', 12); // Police normale
$pdf->Cell($width, $space, mb_convert_encoding('Demandeur : ', 'ISO-8859-1', 'UTF-8'), 0, 0);

// Valeur du demandeur
$pdf->SetFont('Arial', 'B', 12); // Police en gras
$pdf->Cell($width, $space, mb_convert_encoding(htmlspecialchars($demandeur['nom_personnel_tasks']), 'ISO-8859-1', 'UTF-8'), 0, 1);

// Libellé "Service"
$pdf->SetFont('Arial', '', 12); // Police normale
$pdf->Cell($width, $space, mb_convert_encoding('Service bénéficiaire : ', 'ISO-8859-1', 'UTF-8'), 0, 0);

// Valeur du service
$pdf->SetFont('Arial', 'B', 12); // Police en gras
$pdf->Cell($width, $space, mb_convert_encoding(htmlspecialchars($service['lib_service_tasks']), 'ISO-8859-1', 'UTF-8'), 0, 1);

// Libellé "Date de Création"
$pdf->SetFont('Arial', '', 12); // Police normale
$pdf->Cell($width, $space, mb_convert_encoding('Date de Création : ', 'ISO-8859-1', 'UTF-8'), 0, 0);

// Valeur de la date
$pdf->SetFont('Arial', 'B', 12); // Police en gras
$pdf->Cell($width, $space, mb_convert_encoding(htmlspecialchars($helperObj->dateEnFrancaisSansHeure($fiche['date'])), 'ISO-8859-1', 'UTF-8'), 0, 1);

// Libellé "Montant"
$pdf->SetFont('Arial', '', 12); // Police normale
$pdf->Cell($width, $space, mb_convert_encoding('Montant : ', 'ISO-8859-1', 'UTF-8'), 0, 0);

// Valeur du montant
$pdf->SetFont('Arial', 'B', 12); // Police en gras
$pdf->Cell($width, $space, mb_convert_encoding(number_format($fiche['montant'], 2) . ' FCFA', 'ISO-8859-1', 'UTF-8'), 0, 1);

// Remettre la police normale pour d'autres cellules
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5); // Espace entre les sections

$pdf->Ln(5);


// Détails des besoins
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'LISTE DES BESOINS', 0, 1); // Retrait de utf8_decode
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, mb_convert_encoding('Détails des besoins : ', 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, mb_convert_encoding('N°', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(100, 10, mb_convert_encoding('Désignation', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(20, 10, 'Qte', 1);
$pdf->Cell(30, 10, 'PU (FCFA)', 1);
$pdf->Cell(30, 10, 'Total (FCFA)', 1);
$pdf->Ln();

// Récupération des besoins
$pdf->SetFont('Arial', '', 12);
$totalGeneral = 0;
$i = 0;
foreach ($besoins as $besoin) {
    $prixTotal = $besoin['quantite'] * ($besoin['prix_unitaire'] ?? 0);
    $totalGeneral += $prixTotal;
    $i++;

    $pdf->Cell(10, 10, $i, 1);
    $pdf->Cell(100, 10, mb_convert_encoding(htmlspecialchars($besoin['objet']), 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(20, 10, htmlspecialchars($besoin['quantite']), 1);
    $pdf->Cell(30, 10, number_format($besoin['prix_unitaire'] ?? 0, 2), 1);
    $pdf->Cell(30, 10, number_format($prixTotal, 2), 1);
    $pdf->Ln();
}

$pdf->SetFont('Arial', '', 12); // Police normale pour le libellé
$pdf->Cell(150, 10, mb_convert_encoding('Total Général : ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'R'); // Libellé à droite

$pdf->SetFont('Arial', 'B', 12); // Police en gras pour la valeur
$pdf->Cell(0, 10, mb_convert_encoding(number_format($totalGeneral, 2) . ' FCFA', 'ISO-8859-1', 'UTF-8'), 0, 1, 'R'); // Valeur à droite
$pdf->Ln(5);

// Détails des documents justificatifs
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, mb_convert_encoding('DOCUMENTS JUSTIFICATIFS', 'ISO-8859-1', 'UTF-8'), 0, 1); // Retrait de utf8_decode
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, mb_convert_encoding('Les chemins ci-dessous sont cliquables pour accéder aux documents.', 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 255); // Couleur bleu pour les liens
foreach ($files as $fichier) {
    $filePath = htmlspecialchars('https://fidest.ci/performance/request/' . $fichier['file_path']);
    $pdf->Cell(0, 10, $filePath, 0, 1, 'L', false, $filePath); // Lien cliquable
    $y = $pdf->GetY(); // Obtenir la position Y actuelle
    $pdf->Line($pdf->GetX(), $y - 1, $pdf->GetX() + $pdf->GetStringWidth($filePath), $y - 1); // Dessiner une ligne sous le texte
}
$pdf->SetTextColor(0); // Réinitialiser la couleur du texte à la couleur par défaut (noir)


// Sortie du PDF
$pdf->Output('I', 'Fiche_Expression_Besoin_' . $ficheId . '.pdf');
exit;
