<?php

require_once 'Database.php';

class Service {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Ajouter une nouvelle service
    public function ajouterService($libService, $dateCreat, $securAjout) {
        $sql = "INSERT INTO service_tasks (lib_service_tasks, date_creat, secur_ajout) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$libService, $dateCreat, $securAjout]);
    }

    // Modifier une service existante
    public function modifierService($id, $libService, $dateCreat, $securAjout) {
        $sql = "UPDATE service_tasks SET lib_service_tasks = ?, date_creat = ?, secur_ajout = ? WHERE id_service_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$libService, $dateCreat, $securAjout, $id]);
    }

    // Supprimer une service
    public function supprimerService($id) {
        $sql = "DELETE FROM service_tasks WHERE id_service_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lister toutes les services
    public function listerServices() {
        $sql = "SELECT * FROM service_tasks";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir les détails d'une service par ID
    public function obtenirServiceParId($id) {
        $sql = "SELECT * FROM service_tasks WHERE id_service_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtenir les services par libellé
    public function obtenirServiceParLibelle($libService) {
        $sql = "SELECT * FROM service_tasks WHERE lib_service_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$libService]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

