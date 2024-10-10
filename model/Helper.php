<?php
require_once 'Database.php';

class Helper
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function calculerScore($completedDuration, $assignedDuration)
    {
        if ($assignedDuration == 0) {
            return 0; // Éviter division par zéro si aucune tâche n'est assignée
        }

        // Calculer le pourcentage du temps de travail terminé
        $score = ($completedDuration / $assignedDuration) * 100;

        return round($score, 2); // Arrondi à deux décimales
    }

    public function dateEnFrancais($date_time)
    {
        // Créer un objet IntlDateFormatter pour formater la date en français
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
        $formatter->setPattern('EEEE d MMMM yyyy à HH:mm');

        // Convertir la date en timestamp
        $timestamp = strtotime($date_time);

        // Retourner la date formatée
        return $formatter->format($timestamp);
    }

    public function dateEnFrancaisSansHeure($date_time)
    {
        // Créer un objet IntlDateFormatter pour formater la date sans l'heure
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter->setPattern('EEEE d MMMM yyyy');

        // Convertir la date en timestamp
        $timestamp = strtotime($date_time);

        // Retourner la date formatée
        return $formatter->format($timestamp);
    }
}
