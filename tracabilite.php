<?php include('header_tracabilite.php'); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traçabilité Performance</title>
    <link rel="stylesheet" href="plugins/css/all.min.css">
    <script src="plugins/js/jquery-3.6.0.min.js"></script>
    <link href="css/style_tracabilite.css" rel="stylesheet" />
</head>

<body>
    <div class="main-container">
        <div class="container">
            <div class="main-title-container">
                <h1 class="main-title">Logs de Traçabilité</h1>
            </div>
            <div class="logs-container" id="logs-container">
                <!-- Les logs seront affichés ici -->
            </div>
        </div>
    </div>

    <script>
        // Fonction pour récupérer les logs de traçabilité en temps réel
        function fetchLogs() {
            $.ajax({
                url: 'ajax/get_tracabilite_logs.php',
                type: 'GET',
                success: function(response) {
                    $('#logs-container').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Erreur lors de la récupération des logs :', textStatus, errorThrown);
                }
            });
        }

        // Actualisation automatique des logs toutes les 5 secondes
        setInterval(fetchLogs, 5000);

        // Charger les logs immédiatement à l'ouverture de la page
        $(document).ready(function() {
            fetchLogs();
        });
    </script>
</body>

</html>