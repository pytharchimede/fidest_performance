<?php
// Message de succès
$success_message = 'Soumission réussie !';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        .success-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
        }

        .success-icon {
            font-size: 50px;
            color: #fabd02;
            /* Couleur orange pour succès */
            margin-bottom: 20px;
        }

        .success-msg {
            color: #1d2b57;
            /* Bleu pour le texte */
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sub-msg {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .countdown {
            font-size: 14px;
            color: #333;
        }

        .countdown span {
            font-weight: bold;
            color: #fabd02;
            /* Couleur orange */
        }
    </style>
    <script>
        let countdown = 5;

        function updateCountdown() {
            document.getElementById('countdown').innerText = countdown;
            if (countdown <= 0) {
                window.location.href = 'dashboard.php'; // Redirection vers le dashboard
            }
            countdown--;
        }
        setInterval(updateCountdown, 1000);
    </script>
</head>

<body>

    <div class="success-container">
        <div class="success-icon">
            &#x2714; <!-- Icône de succès -->
        </div>
        <div class="success-msg"><?= htmlspecialchars($success_message); ?></div>
        <div class="sub-msg">
            Votre requête a été soumise avec succès.
        </div>
        <div class="countdown">
            Vous serez redirigé vers votre tableau de bord dans <span id="countdown">5</span> secondes.
        </div>
    </div>

    <script src="js/style_script.js"></script>
</body>

</html>