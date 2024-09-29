<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire en cours de création</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            position: relative;
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .icon {
            font-size: 80px;
            color: #fabd02;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        h1 {
            font-size: 24px;
            color: #1d2b57;
            margin-bottom: 20px;
        }

        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .progress {
            width: 50%;
            height: 10px;
            background-color: #1d2b57;
            border-radius: 10px;
            animation: load 5s infinite;
        }

        @keyframes load {
            0% {
                width: 0;
            }
            100% {
                width: 100%;
            }
        }

        .back-button {
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            background-color: #1d2b57;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .back-button i {
            margin-right: 10px;
        }

        .back-button:hover {
            background-color: #fabd02;
            color: #1d2b57;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="icon">
            😊
        </div>
        <h1>Nous préparons votre formulaire... Merci de patienter !</h1>

        <div class="progress-bar">
            <div class="progress"></div>
        </div>

        <a href="javascript:history.back()" class="back-button">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

</body>
</html>
