<?php
require_once 'model/Personnel.php';
require_once 'model/Helper.php';
require_once 'model/Fonction.php';
require_once 'request/phpqrcode/qrlib.php';

$personnelObj = new Personnel();
$helperObj = new Helper();
$fonctionObj = new Fonction();



$allEmployees = $personnelObj->listerPersonnel();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges Employés</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_badge_employe.css">
    <style>
        body {
            background-color: #f1f3f5;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .badge-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* 2 badges par ligne */
            gap: 20px;
            /* Espace entre les badges */
            margin-top: 50px;
            padding: 20px;
        }

        .badge-card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 8, 5cm;
            height: 5, 5cm;
            /* Occupe toute la colonne */
            padding: 20px;
            display: flex;
            align-items: flex-start;
            position: relative;
        }

        .company-logo {
            width: 80px;
            height: auto;
            position: absolute;
            top: 10px;
            left: 18%;
            transform: translateX(-50%);
        }

        .profile-info {
            display: flex;
            width: 100%;
        }

        .employee-photo {
            width: 120px;
            height: 150px;
            border: 4px solid #1d2b57;
            object-fit: cover;
            margin-right: 20px;
            margin-top: 30px;
        }

        .info {
            flex-grow: 1;
        }

        .document-name {
            background-color: #1d2b57;
            color: #ffffff;
            font-size: 22px;
            margin: 0;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
        }

        .employee-name {
            font-size: 24px;
            color: #1d2b57;
            margin: 10px 0;
        }

        .employee-profession {
            font-size: 18px;
            color: #888;
            margin: 5px 0;
        }

        .employee-id,
        .employee-contact,
        .employee-email {
            font-size: 12px;
            color: #555555;
            margin-bottom: 5px;
            text-align: left;
        }

        .btn-print {
            background-color: #1d2b57;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-print:hover {
            background-color: #fabd02;
            color: #1d2b57;
        }

        .employee-photo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px;
        }

        .employee-qrcode {
            width: 50px;
            height: auto;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="badge-container">
        <?php foreach ($allEmployees as $employeeDetails): ?>
            <?php
            $employeeId = $employeeDetails['id_personnel_tasks'];
            $attendanceData = $personnelObj->getAttendanceDataById($employeeId);
            $tasksData = $personnelObj->getTasksDataById($employeeDetails['matricule_personnel_tasks']);
            $date_entree = $helperObj->dateEnFrancaisSansHeure(date_time: $employeeDetails['date_recrutement']);
            $qrCodeData = "https://fidest.ci/performance/profil_personnel_tasks.php?id='.$employeeId.'";
            $qrCodeFilePath = 'request/qrcode/' . $employeeId . '_qrcode.png';
            QRcode::png($qrCodeData, $qrCodeFilePath, QR_ECLEVEL_L, 4);
            $fonction = $fonctionObj->obtenirFonctionParId($employeeDetails['fonction_id']);
            ?>
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
                        <p class="employee-profession"><em> <?= is_array($fonction) && isset($fonction['lib_fonction_tasks']) && $fonction['lib_fonction_tasks'] != 0
                                                                ? $fonction['lib_fonction_tasks']
                                                                : 'Non Définie' ?></em></p>
                        <p class="employee-id">Matricule: <?= strtoupper($employeeDetails['matricule_personnel_tasks']) ?></p>
                        <p class="employee-contact"><strong>Téléphone:</strong> <?= $employeeDetails['tel_personnel_tasks'] ?></p>
                        <p class="employee-email"><strong>Email:</strong> <?= $employeeDetails['email_personnel_tasks'] ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="btn btn-print" onclick="window.print();">
        <i class="fas fa-print"></i> Imprimer tous les badges
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="js/style_script.js"></script>
</body>

</html>