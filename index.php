<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Personnel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <button type="submit" class="btn login-btn">Se connecter</button>
        <?php if(isset($error_message)) { ?>
            <div class="error-msg"><?= $error_message; ?></div>
        <?php } ?>
    </form>
</div>

</body>
</html>