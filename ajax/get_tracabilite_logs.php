<?php
require '../model/Database.php'; // Inclure le fichier de connexion à la base de données
$databaseObj = new Database();

$pdo = $databaseObj->getConnection();

try {
    // Préparation de la requête SQL pour récupérer les logs
    $sql = "SELECT * FROM tracabilite_performance ORDER BY date_tracabilite DESC, heure_tracabilite DESC";
    $stmt = $pdo->query($sql);

    // Vérifie s'il y a des résultats
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="log-entry">';
            echo '<p><strong>Action :</strong> ' . htmlspecialchars($row['libelle_tracabilite']) . '</p>';
            echo '<p class="log-date">Date : ' . $row['date_tracabilite'] . '</p>';
            echo '<p class="log-heure">Heure : ' . $row['heure_tracabilite'] . '</p>';
            echo '<p class="log-ip"><strong>IP :</strong> ' . $row['ip_tracabilite_performance'] . ' <strong>Port :</strong> ' . $row['port_tracabilite_performance'] . '</p>';
            echo '<p class="log-agent"><strong>User Agent :</strong> ' . htmlspecialchars($row['user_agent']) . '</p>';
            echo '<p class="log-matricule"><strong>Matricule :</strong> ' . $row['matricule'] . '</p>';
            echo '<p><strong>Latitude :</strong> ' . $row['latitude'] . ' <strong>Longitude :</strong> ' . $row['longitude'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Aucune trace de performance n\'a été trouvée.</p>';
    }
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des logs : ' . $e->getMessage();
}
