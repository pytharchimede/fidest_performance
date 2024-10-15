<?php
session_start();

// Si un message d'erreur est défini, on le récupère
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Personnel</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lien vers Font Awesome pour l'icône d'œil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-title {
            font-size: 24px;
            color: #1d2b57;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-btn {
            background-color: #1d2b57;
            color: #fff;
            font-size: 16px;
            padding: 10px;
            border-radius: 30px;
            width: 100%;
        }

        .login-btn:hover {
            background-color: #fabd02;
            color: #fff;
        }

        .error-msg {
            color: #ff3333;
            font-size: 14px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
                max-width: 300px;
            }
        }

        /** Oeil visualisation de mot de passe */
        .field-icon {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
        }

        .position-relative {
            position: relative;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h1 class="login-title">Connexion</h1>
        <form action="request/control.php" method="post">
            <div class="form-group">
                <label for="matricule">Numéro Matricule</label>
                <input type="text" class="form-control" id="matricule" name="matricule" placeholder="Entrez votre matricule" required>
            </div>

            <!-- Affichage du champ mot de passe uniquement si la session le demande -->
            <?php if (isset($_SESSION['demande_password']) && $_SESSION['demande_password']) { ?>
                <div class="form-group position-relative">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                </div>
            <?php } ?>

            <button type="submit" class="btn login-btn">Se connecter</button>
            <?php if ($error_message) { ?>
                <div class="error-msg"><?= $error_message; ?></div>
            <?php } ?>
        </form>
    </div>

    <script src="js/style_script.js"></script>
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            let passwordInput = document.querySelector('#password');
            let icon = this;
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        });
    </script>
</body>

</html>