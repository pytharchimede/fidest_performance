<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser mot de passe</title>
    <link href="plugins/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            max-width: 450px;
            margin: 80px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-weight: 600;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
        }

        .btn-primary {
            background-color: #1d2b57;
            border-color: #1d2b57;
            font-weight: 600;
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .btn-primary:disabled {
            background-color: #ccc;
            border-color: #ccc;
            cursor: not-allowed;
        }

        .spinner-border {
            display: none;
            margin-left: 10px;
        }

        .form-control {
            border-radius: 8px;
        }

        .otp-input {
            display: flex;
            justify-content: space-between;
        }

        .otp-input input {
            width: 48px;
            height: 48px;
            font-size: 24px;
            text-align: center;
            margin: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .alert {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Réinitialiser votre mot de passe</h2>

        <!-- Affichage des messages d'erreur, le cas échéant -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Formulaire de demande d'envoi d'OTP via le numéro de téléphone -->
        <form id="otpForm" method="post">
            <div class="form-group">
                <label for="phone">Numéro de téléphone</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone" maxlength="10" pattern="\d{10}" required>
            </div>
            <button type="submit" class="btn btn-primary" id="sendOtpBtn">
                Envoyer OTP
                <div class="spinner-border spinner-border-sm" role="status" id="spinner">
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </form>

        <!-- Formulaire de saisie de l'OTP -->
        <form action="request/verify_otp.php" method="post" class="mt-4">
            <div class="form-group">
                <label for="otp">Entrez OTP</label>
                <div class="otp-input">
                    <input type="text" maxlength="1" name="otp[]" required>
                    <input type="text" maxlength="1" name="otp[]" required>
                    <input type="text" maxlength="1" name="otp[]" required>
                    <input type="text" maxlength="1" name="otp[]" required>
                    <input type="text" maxlength="1" name="otp[]" required>
                    <input type="text" maxlength="1" name="otp[]" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Vérifier OTP</button>
        </form>

    </div>

    <!-- Liens vers jQuery -->
    <script src="plugins/js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#phone').on('input', function() {
                this.value = this.value.replace(/\D/g, ''); // Remplace tout caractère non numérique par une chaîne vide
            });

            $('#otpForm').on('submit', function(e) {
                e.preventDefault(); // Empêche le rechargement de la page

                // Désactiver le champ de téléphone et le bouton, afficher le spinner
                $('#phone').prop('disabled', true); // Griser le champ numéro
                $('#sendOtpBtn').prop('disabled', true); // Griser le bouton
                $('#spinner').show(); // Afficher le spinner sur le bouton

                // Récupérer le numéro de téléphone
                var phone = $('#phone').val();

                // Envoi de la requête AJAX pour demander l'OTP
                $.ajax({
                    url: 'request/send_otp.php', // Endpoint PHP pour gérer l'envoi OTP
                    type: 'POST',
                    data: {
                        phone: phone
                    },
                    success: function(response) {
                        // Logique de succès (affichage du message, redirection, etc.)
                        console.log('OTP envoyé avec succès !');
                        // Ici, vous pouvez afficher un message de succès, ou tout autre action à suivre.
                        $('#spinner').hide(); // Afficher le spinner sur le bouton

                    },
                    error: function() {
                        // Réactiver le champ et le bouton en cas d'erreur
                        $('#phone').prop('disabled', false); // Réactiver le champ
                        $('#sendOtpBtn').prop('disabled', false); // Réactiver le bouton
                        $('#spinner').hide(); // Cacher le spinner
                        alert('Erreur lors de l\'envoi de l\'OTP. Veuillez réessayer.');
                    }
                });
            });
        });
    </script>
</body>

</html>