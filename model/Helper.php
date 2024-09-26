<?php
require_once 'Database.php';

class Helper {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function calculerScore($completedDuration, $assignedDuration) {
        if ($assignedDuration == 0) {
            return 0; // Éviter division par zéro si aucune tâche n'est assignée
        }

        // Calculer le pourcentage du temps de travail terminé
        $score = ($completedDuration / $assignedDuration) * 100;

        return round($score, 2); // Arrondi à deux décimales
    }
    

    public function dateEnFrancais($date_time){
        // Configurer la locale en français
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'french'); // Pour Linux
        // Si vous êtes sur un serveur Windows, utilisez 'french' :
        // setlocale(LC_TIME, 'french');

        // Convertir la date en timestamp
        $timestamp = strtotime($date_time);

        // Formater et afficher la date en français
        return strftime('%A %d %B %Y à %H:%M', $timestamp);
    }

}
?>