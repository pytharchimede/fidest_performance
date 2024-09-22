<?php
require_once 'Database.php';

class Helper {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function calculerScore($value1, $value2) {
        // Convertir les valeurs en flottants pour les calculs
        $qt1 = floatval($value1);
        $qt2 = floatval($value2);

        // Vérifier si $qt2 est différent de zéro pour éviter la division par zéro
        if ($qt2 == 0) {
            // Retourner 0 ou une valeur appropriée si la division par zéro est une situation non valide
            return 0;
        }

        // Calculer le score en pourcentage
        $score = ($qt1 / $qt2) * 100;

        // Vérifier si le score est négatif et le corriger si nécessaire
        if ($score < 0) {
            $score = 0;
        }

        // Retourner le score calculé
        return $score;
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
