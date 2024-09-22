<?php

require_once 'Database.php';

class Fonction {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Ajouter une nouvelle fonction
    public function ajouterFonction($libFonction, $dateCreat, $securAjout) {
        $sql = "INSERT INTO fonction_tasks (lib_fonction_tasks, date_creat, secur_ajout) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$libFonction, $dateCreat, $securAjout]);
    }

    // Modifier une fonction existante
    public function modifierFonction($id, $libFonction, $dateCreat, $securAjout) {
        $sql = "UPDATE fonction_tasks SET lib_fonction_tasks = ?, date_creat = ?, secur_ajout = ? WHERE id_fonction_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$libFonction, $dateCreat, $securAjout, $id]);
    }

    // Supprimer une fonction
    public function supprimerFonction($id) {
        $sql = "DELETE FROM fonction_tasks WHERE id_fonction_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lister toutes les fonctions
    public function listerFonctions() {
        $sql = "SELECT * FROM fonction_tasks";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir les détails d'une fonction par ID
    public function obtenirFonctionParId($id) {
        $sql = "SELECT * FROM fonction_tasks WHERE id_fonction_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtenir les fonctions par libellé
    public function obtenirFonctionParLibelle($libFonction) {
        $sql = "SELECT * FROM fonction_tasks WHERE lib_fonction_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$libFonction]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

