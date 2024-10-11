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

// Récupérer les détails de la demande
$idDemande = $_GET['id'] ?? null;
$req = "SELECT * FROM demande_pret WHERE id = :idDemande";
$stmt = $pdo->prepare($req);
$stmt->execute([':idDemande' => $idDemande]);
$demandeDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la demande n'existe pas
if (!$demandeDetails) {
    echo "Demande introuvable.";
    exit();
}

// Récupérer les informations de l'employé
$reqEmp = "SELECT * FROM personnel_tasks WHERE id_personnel_tasks = :idPersonnel";
$stmtEmp = $pdo->prepare($reqEmp);
$stmtEmp->execute([':idPersonnel' => $demandeDetails['id_personnel_tasks']]);
$employeeDetails = $stmtEmp->fetch(PDO::FETCH_ASSOC);

// Création de la classe PDF
class PDF extends FPDF
{
    // En-tête
    function Header()
    {
        $this->Image('../img/logo_fidest.jpg', 10, 10, 40);
        // $this->Image('../img/logo_veritas.jpg', 150, 10, 40);

        $this->Ln(35);
        $this->SetFont('Arial', 'BU', 22);
        $this->Cell(0, 10, 'FICHE DE DEMANDE DE PRET', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pied de page
    function Footer()
    {

        // Dessiner une ligne grise
        $this->SetDrawColor(0, 0, 0); // Couleur de la ligne grise
        $this->Line(10, 272, 200, 272); // Position de la ligne (10 mm du bord gauche à 260 mm du bord haut)


        // Position at 1.5 cm from bottom
        $this->SetY(-22);

        //    $this->Image('../../img/logo_veritas.jpg', 10,275,30);	

        // Arial italic 8
        $this->SetFont('Arial', '', 7);

        $this->Cell(0, 3.5, utf8_decode("FOURNITURES INDUSTRIELLES, DEPANNAGE ET TRAVAUX PUBLIQUES - Au capital de 10 000 000 F CFA - Siège Social : Abidjan, Koumassi, Zone industrielle"), 0, 1, 'C');
        $this->Cell(0, 3.5, utf8_decode("01 BP 1642 Abidjan 01 - Téléphone : (+225) +225 27-21-36-27-27  -  Email : info@fidest.org - RCCM : CI-ABJ-2017-B-20163  -  N° CC : 010274200088"), 0, 1, 'C');

        //	$this->Image('logo.jpg', 172,275,30);	

        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation de la classe PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Informations sur la demande de prêt
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Informations de la Demande', 0, 1, 'C');

$largeurCle = 120;  // Augmenter la largeur de la clé
$largeurValeur = 100;  // Ajuster la largeur de la valeur

$pdf->Ln(10);

// Affichage des détails de la demande
$pdf->SetFont('Arial', '', 14);
$pdf->Cell($largeurValeur, 10, utf8_decode('Désignation du prêt:'), 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, utf8_decode($demandeDetails['designation_pret']), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Nom et Prénom(s):'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, strtoupper($demandeDetails['nom_prenom']), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, 'Matricule:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, $demandeDetails['matricule'], 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Montant demandé (FCFA):'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, number_format($demandeDetails['montant_demande'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, 'Montant recouvrement partiel (FCFA):', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, number_format($demandeDetails['montant_recouvrement_partiel'], 0, ',', ' ') . ' FCFA', 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, utf8_decode('Date début recouvrement:'), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, date("d/m/Y", strtotime($demandeDetails['date_debut_recouvrement'])), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Cell($largeurValeur, 10, 'Date fin recouvrement:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($largeurCle, 10, date("d/m/Y", strtotime($demandeDetails['date_fin_recouvrement'])), 0, 1, 'L');
$pdf->SetFont('Arial', '', 14);

$pdf->Ln(12);

$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(10, 10, 'Demandeur', 0, 0, 'L');
$pdf->Cell(160, 10, 'Responsable RH', 0, 0, 'C');
$pdf->Cell(10, 10, utf8_decode('Directeur Général'), 0, 1, 'R');

$pdf->Ln(50);

$pdf->SetFont('Arial', '', 12);

// Créer un objet DateTime à partir de la date de création
$date = new DateTime($demandeDetails['date_creat']);

// Créer un formateur pour afficher la date avec le jour de la semaine en toutes lettres
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN);

// Formater la date avec le jour en toutes lettres
$dateEnToutesLettres = $formatter->format($date);

// Ajouter la ligne au PDF avec la date en toutes lettres et le jour
$pdf->Cell(0, 10, utf8_decode('Fait à Abidjan, le ') . $dateEnToutesLettres, 0, 1, 'R');


$pdf->SetFont('Arial', '', 14);

// Sauvegarder ou afficher le PDF
$pdf->Output('I', 'Fiche_Demande_Pret_' . date('d_m_Y') . '.pdf');

// Fermer la connexion à la base de données
unset($pdo);
