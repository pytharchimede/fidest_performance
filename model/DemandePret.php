<?php
require_once 'Database.php';

class DemandePret {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Ajouter une nouvelle demande de prêt
    public function ajouterDemandePret($designation_pret, $nom, $matricule, $montant_demande, $montant_recouvrement_partiel, $date_debut_recouvrement, $date_fin_recouvrement, $date_creat, $securAjout, $statut) {
        $sql = "INSERT INTO demande_pret (designation_pret, nom_prenom, matricule, montant_demande, montant_recouvrement_partiel, date_debut_recouvrement, date_fin_recouvrement, date_creat, securAjout, statut) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$designation_pret, $nom, $matricule, $montant_demande, $montant_recouvrement_partiel, $date_debut_recouvrement, $date_fin_recouvrement, $date_creat, $securAjout, $statut]);
    }    

    // Lire toutes les demandes de prêt
    public function lireDemandesPrets() {
        $sql = "SELECT * FROM demande_pret";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lire une demande de prêt par ID
    public function lireDemandePret($id) {
        $sql = "SELECT * FROM demande_pret WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour une demande de prêt
    public function mettreAJourDemandePret($id, $designation_pret, $nom, $matricule, $montant_demande, $montant_recouvrement_partiel, $date_debut_recouvrement, $date_fin_recouvrement, $statut) {
        $sql = "UPDATE demande_pret 
                SET designation_pret = ?, nom_prenom = ?, matricule = ?, montant_demande = ?, montant_recouvrement_partiel = ?, date_debut_recouvrement = ?, date_fin_recouvrement = ?, statut = ? 
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$designation_pret, $nom, $matricule, $montant_demande, $montant_recouvrement_partiel, $date_debut_recouvrement, $date_fin_recouvrement, $statut, $id]);
    }    

    // Supprimer une demande de prêt
    public function supprimerDemandePret($id) {
        $sql = "DELETE FROM demande_pret WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
