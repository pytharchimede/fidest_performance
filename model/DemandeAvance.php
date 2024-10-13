<?php
require_once 'Database.php';

class DemandeAvance
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Ajouter une nouvelle demande d'avance
    public function ajouterDemandeAvance($nom, $matricule, $fonction, $service, $motif, $montant, $date_creat, $statut)
    {
        $sql = "INSERT INTO demande_avance_salaire (nom, matricule, fonction, service, motif, montant, date_creat, statut) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $matricule, $fonction, $service, $motif, $montant, $date_creat, $statut]);
    }

    // Lire toutes les demandes d'avance
    public function lireDemandesAvances()
    {
        $sql = "SELECT * FROM demande_avance_salaire";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lire une seule demande d'avance par ID
    public function lireDemandeAvance($id)
    {
        $sql = "SELECT * FROM demande_avance_salaire WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une demande d'avance
    public function mettreAJourDemandeAvance($id, $nom, $matricule, $fonction, $service, $motif, $montant, $statut)
    {
        $sql = "UPDATE demande_avance_salaire 
                SET nom = ?, matricule = ?, fonction = ?, service = ?, motif = ?, montant = ?, statut = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nom, $matricule, $fonction, $service, $motif, $montant, $statut, $id]);
    }

    // Supprimer une demande d'avance
    public function supprimerDemandeAvance($id)
    {
        $sql = "DELETE FROM demande_avance_salaire WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
