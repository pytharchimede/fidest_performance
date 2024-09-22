<?php
session_start();
require_once '../model/Database.php';

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
$req = "SELECT p.id_personnel_tasks, p.nom_personnel_tasks, pt.date_pointage, pt.present 
        FROM personnel_tasks AS p
        LEFT JOIN pointage_personnel AS pt ON p.id_personnel_tasks = pt.personnel_tasks_id 
        WHERE pt.date_pointage BETWEEN :dateDebut AND :dateFin";
$records = $pdo->prepare($req);
$records->execute([':dateDebut' => $dateDebut, ':dateFin' => $dateFin]);

// Créer un tableau pour stocker les données
$pointages = [];
while ($row = $records->fetch()) {
    $pointages[$row['nom_personnel_tasks']][$row['date_pointage']] = $row['present'] ? utf8_decode('Présent') : 'Absent';
}

// Configuration de l'en-tête pour le téléchargement du fichier Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=pointage_" . $anneeCourante . "_" . $moisCourant . ".xls");

// Création du tableau HTML
echo '<table style="border:1px solid; border-collapse: collapse;">
          <tr style="font-size:14px; font-weight:500; border:1px solid; background-color:#f5f6f8;">
                <th style="border:1px solid;">Nom Personnel</th>';

// Ajouter les dates comme en-têtes de colonnes
foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date) {
    echo '<th style="border:1px solid;">' . htmlspecialchars($date->format("Y-m-d")) . '</th>';
}
echo utf8_decode('<th style="border:1px solid;">Total Jours Travaillés</th>'); // Nouvelle colonne pour le total
echo '</tr>';

// Remplir le tableau avec les données
foreach ($pointages as $nom => $statuts) {
    echo '<tr style="border:1px solid;">
            <td style="border:1px solid;">' . htmlspecialchars($nom) . '</td>';
    
    $totalJoursTravailles = 0; // Initialiser le compteur

    foreach (new DatePeriod(new DateTime($dateDebut), new DateInterval('P1D'), new DateTime($dateFin . ' +1 day')) as $date) {
        $dateFormat = $date->format("Y-m-d");
        $statut = isset($statuts[$dateFormat]) ? $statuts[$dateFormat] : 'Absent'; // Par défaut, absent
        
        // Déterminer la couleur pour le statut
        $couleur = ($statut === utf8_decode('Présent')) ? '#00FF00' : '#FF0000'; // Vert pour présent, rouge pour absent
        $texteCouleur = 'white'; // Texte en blanc

        echo '<td style="border:1px solid; background-color:' . $couleur . '; color:' . $texteCouleur . ';">' . utf8_decode($statut) . '</td>';

        // Compter les jours travaillés
        if ($statut === utf8_decode('Présent')) {
            $totalJoursTravailles++;
        }
    }
    
    // Afficher le total de jours travaillés
    echo '<td style="border:1px solid;">' . htmlspecialchars($totalJoursTravailles) . '</td>';
    echo '</tr>';
}

echo '</table>';

unset($pdo);

header('../view_pointage_web.php');

?>
