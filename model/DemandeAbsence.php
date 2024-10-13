<?php
require_once 'Database.php';

class DemandeAbsence
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Ajouter une nouvelle demande d'absence
    public function ajouterDemandeAbsence($nom, $matricule, $fonction, $service, $motif, $date_depart, $date_retour, $nombre_jours, $statut, $date_creat)
    {
        $sql = "INSERT INTO demande_absence (nom, matricule, fonction, service, motif, date_depart, date_retour, nombre_jours, statut, date_creat) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $matricule, $fonction, $service, $motif, $date_depart, $date_retour, $nombre_jours, $statut, $date_creat]);
    }

    // Lire toutes les demandes d'absence
    public function lireDemandesAbsences()
    {
        $sql = "SELECT * FROM demande_absence";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lire une seule demande d'absence par ID
    public function lireDemandeAbsence($id)
    {
        $sql = "SELECT * FROM demande_absence WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une demande d'absence
    public function mettreAJourDemandeAbsence($id, $nom, $matricule, $fonction, $service, $motif, $date_depart, $date_retour, $nombre_jours, $statut)
    {
        $sql = "UPDATE demande_absence 
                SET nom = ?, matricule = ?, fonction = ?, service = ?, motif = ?, date_depart = ?, date_retour = ?, nombre_jours = ?, statut = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $matricule, $fonction, $service, $motif, $date_depart, $date_retour, $nombre_jours, $statut, $id]);
    }

    // Supprimer une demande d'absence
    public function supprimerDemandeAbsence($id)
    {
        $sql = "DELETE FROM demande_absence WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
