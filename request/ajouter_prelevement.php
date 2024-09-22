<?php
// Connexion à la base de données et démarrage de la session
session_start();
require_once '../model/Database.php';

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");
    exit();
}

if($_SESSION['acces_rh']!=1){
    header('Location: ../acces_refuse.php');
}

// Vérification des données du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getConnection();

    $personnel_id = $_POST['personnel_id'];
    $type_prelevement = $_POST['type_prelevement'];
    $montant_total = isset($_POST['montant_total']) ? $_POST['montant_total'] : null;
    $montant_recurrent = isset($_POST['montant_recurrent']) ? $_POST['montant_recurrent'] : null;
    $nombre_prelevements_restants = isset($_POST['nombre_prelevements_restants']) ? $_POST['nombre_prelevements_restants'] : null;
    $montant = isset($_POST['montant']) ? $_POST['montant'] : null;

    try {
        // Préparation et exécution de la requête SQL
        $sql = "INSERT INTO prelevements (personnel_id, type_prelevement, montant_total, montant_recurrent, nombre_prelevements_restants, montant)
                VALUES (:personnel_id, :type_prelevement, :montant_total, :montant_recurrent, :nombre_prelevements_restants, :montant)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':personnel_id' => $personnel_id,
            ':type_prelevement' => $type_prelevement,
            ':montant_total' => $montant_total,
            ':montant_recurent' => $montant_recurrent,
            ':nombre_prelevements_restants' => $nombre_prelevements_restants,
            ':montant' => $montant,
        ]);

        // Définir un message de succès et un délai de redirection
        $success_message = "Prélèvement enregistré avec succès !";
        $redirection_url = '../liste_personnel.php';
        $delay = 5;

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        header("Location: ../liste_personnel.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .success-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .success-msg {
            color: #28a745;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .countdown {
            font-size: 16px;
        }
    </style>
    <script>
        let countdown = <?= $delay; ?>;
        function updateCountdown() {
            document.getElementById('countdown').innerText = countdown;
            if (countdown <= 0) {
                window.location.href = '<?= $redirection_url; ?>';
            }
            countdown--;
        }
        setInterval(updateCountdown, 1000);
    </script>
</head>
<body>

<div class="success-container">
    <div class="success-msg"><?= htmlspecialchars($success_message); ?></div>
    <div class="countdown">Vous serez redirigé vers la liste du personnel dans <span id="countdown"><?= $delay; ?></span> secondes.</div>
</div>

</body>
</html>
