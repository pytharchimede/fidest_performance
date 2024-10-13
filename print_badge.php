<?php
require_once 'model/Personnel.php';
require_once 'model/Helper.php';
require_once 'model/Fonction.php';
require_once 'request/phpqrcode/qrlib.php';

$personnelObj = new Personnel();
$helperObj = new Helper();
$fonctionObj = new Fonction();

if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $employeeDetails = $personnelObj->getPersonnelById($employeeId);
    // Supposons que vous récupérez également les données d'assiduité et de rendement ici.
    $attendanceData = $personnelObj->getAttendanceDataById($employeeId);
    $tasksData = $personnelObj->getTasksDataById($employeeDetails['matricule_personnel_tasks']);

    $date_entree = $helperObj->dateEnFrancaisSansHeure($employeeDetails['date_recrutement']);


    // Génération du QR code
    $qrCodeData = "https://fidest.ci/performance/profil_personnel_tasks.php?id='.$employeeId.'";
    $qrCodeFilePath = 'request/qrcode/' . $employeeId . '_qrcode.png'; // Chemin où vous souhaitez enregistrer le QR code
    QRcode::png($qrCodeData, $qrCodeFilePath, QR_ECLEVEL_L, 4);

    $fonction = $fonctionObj->obtenirFonctionParId($employeeDetails['fonction_id']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badge Employé</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_badge_employe.css">
</head>

<body>
    <div class="badge-container">
        <div class="badge-card">
            <img src="https://fidest.ci/logi/img/logo_connex.png" alt="Logo Entreprise" class="company-logo">
            <div class="profile-info">
                <div class="employee-photo-container">
                    <img src="https://stock.fidest.ci/app/&_gestion/photo/<?= $employeeDetails['photo_personnel_tasks'] ?>" alt="Photo Employé" class="employee-photo">
                    <img src="request/qrcode/<?= $employeeId ?>_qrcode.png" alt="QR Code" class="employee-qrcode">
                </div>
                <div class="info">
                    <h2 class="document-name">CARTE PROFESSIONNELLE</h2>
                    <h2 class="employee-name"><?= strtoupper($employeeDetails['nom_personnel_tasks']) ?></h2>
                    <p class="employee-profession"><em><?= $fonction['lib_fonction_tasks'] ?></em></p>
                    <p class="employee-id">Matricule: <?= strtoupper($employeeDetails['matricule_personnel_tasks']) ?></p>
                    <p class="employee-contact"><strong>Téléphone:</strong> <?= $employeeDetails['tel_personnel_tasks'] ?></p>
                    <p class="employee-email"><strong>Email:</strong> <?= $employeeDetails['email_personnel_tasks'] ?></p>
                </div>
            </div>

        </div>
        <button class="btn btn-print" onclick="window.print();">
            <i class="fas fa-print"></i> Imprimer le badge
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>