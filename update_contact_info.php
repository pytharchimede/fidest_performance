<?php
session_start();
require_once 'model/Database.php';

// Récupérer les informations de l'utilisateur
$pdo = Database::getConnection();
$sql = "SELECT email_personnel_tasks, tel_personnel_tasks FROM personnel_tasks WHERE id_personnel_tasks = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_SESSION['id_personnel_tasks']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mettre à jour les informations de contact</title>
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Materialize CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">

    <!-- Style personnalisé -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background: #f5f5f5;
        }
        main {
            flex: 1 0 auto;
        }
        .container {
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .input-field label {
            color: #1d2b57; /* Couleur neutre pour les labels */
        }
        .input-field input[type="text"], 
        .input-field input[type="email"], 
        .input-field input[type="password"] {
            border-bottom: 2px solid #1d2b57; /* Bordure des champs */
        }
        .input-field input:focus + label {
            color: #fabd02 !important; /* Couleur au focus */
        }
        .input-field input:focus {
            border-bottom: 2px solid #fabd02 !important;
        }
        button {
            background-color: #fabd02;
            border-radius: 25px;
            padding: 10px 30px;
            color: #1d2b57;
        }
        button:hover {
            background-color: #ffca45;
        }
        h3 {
            color: #1d2b57;
        }
        .alert {
            background-color: #ffeb3b;
            color: #212121;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .icon-warning {
            font-size: 1.5rem;
            vertical-align: middle;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<main>
    <div class="container">
        <!-- Message d'information -->
        <div class="alert">
            <i class="material-icons icon-warning">warning</i>
            <span>Tous les utilisateurs doivent définir un mot de passe pour leurs prochaines connexions.</span>
        </div>

        <h3 class="center-align">Mettre à jour vos informations</h3>
        
        <div class="row">
            <form action="request/update_infos.php" method="post" class="col s12">
                <!-- Champ email -->
                <div class="row">
                    <div class="input-field col s12">
                        <input id="email" name="email" type="email" value="<?php echo $user['email_personnel_tasks']; ?>" <?php if($user['email_personnel_tasks']!=''){ echo 'readonly';} ?> required>
                        <label for="email">Adresse email</label>
                    </div>
                </div>
                
                <!-- Champ téléphone -->
                <div class="row">
                    <div class="input-field col s12">
                        <input id="tel" name="tel" type="text" value="<?php echo $user['tel_personnel_tasks']; ?>" <?php if($user['tel_personnel_tasks']!=''){ echo 'readonly';} ?> required>
                        <label for="tel">Numéro de téléphone</label>
                    </div>
                </div>

                <!-- Champ mot de passe -->
                <div class="row">
                    <div class="input-field col s12">
                        <input id="password" name="password" type="password" required>
                        <label for="password">Mot de passe</label>
                    </div>
                </div>
                
                <!-- Bouton soumettre -->
                <div class="row">
                    <div class="col s12 center-align">
                        <button style="background-color: #1d2b57;" type="submit" class="btn">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Materialize JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    // Initialisation des composants Materialize
    document.addEventListener('DOMContentLoaded', function() {
        M.updateTextFields(); // Initialiser les labels flottants
    });
</script>

</body>
</html>
