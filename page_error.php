<?php
session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "Erreur inconnue.";
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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

        .error-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
        }

        .error-icon {
            font-size: 50px;
            color: #ff3333;
            margin-bottom: 20px;
        }

        .error-msg {
            color: #333;
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
            color: #ff3333;
        }
    </style>
    <script>
        let countdown = 5;

        function updateCountdown() {
            document.getElementById('countdown').innerText = countdown;
            if (countdown <= 0) {
                window.location.href = 'index.php';
            }
            countdown--;
        }
        setInterval(updateCountdown, 1000);
    </script>
</head>

<body>

    <div class="error-container">
        <div class="error-icon">
            &#9888; <!-- Icône d'avertissement -->
        </div>
        <div class="error-msg"><?= htmlspecialchars($error_message); ?></div>
        <div class="sub-msg">
            Une erreur est survenue. Vous serez redirigé vers la page d'accueil.
        </div>
        <div class="countdown">
            Redirection dans <span id="countdown">5</span> secondes.
        </div>
    </div>

    <script src="js/style_script.js"></script>
</body>

</html>