<?php
require_once 'Database.php';

class Task {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function create($task_code, $description, $assigned_to, $deadline, $statut, $duree, $matricule_assignateur) {
        $query = "INSERT INTO tasks (task_code, description, assigned_to, deadline, statut, duree, matricule_assignateur) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$task_code, $description, $assigned_to, $deadline, $statut, $duree, $matricule_assignateur]);
    }

    public function getTasksByStatus($statut) {
        $query = "SELECT * FROM tasks WHERE statut = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$statut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTasks() {
        $query = "SELECT * FROM tasks ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByMatriculeAndStatus($matricule, $statut) {
        $query = "SELECT * FROM tasks WHERE assigned_to = ? AND statut = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$matricule, $statut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByMatricule($matricule) {
        $query = "SELECT * FROM tasks WHERE assigned_to = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$matricule]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksByMatriculeAndAssignateur($matriculeAssignateur) {
        $query = "SELECT * FROM tasks WHERE matricule_assignateur = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$matriculeAssignateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReportRequestedTasksByMatriculeAndAssignateur($matriculeAssignateur) {
        $query = "SELECT * FROM tasks WHERE matricule_assignateur = ? AND report_demande=1 AND report_decide!=1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$matriculeAssignateur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTasksResponsable($id_task){
        $query = "SELECT * FROM tasks LEFT JOIN personnel_tasks ON personnel_tasks.matricule_personnel_tasks=tasks.assigned_to WHERE tasks.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_task]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   

    public function getTasksAssignateur($id_task){
        $query = "SELECT * FROM tasks LEFT JOIN personnel_tasks ON personnel_tasks.matricule_personnel_tasks=tasks.matricule_assignateur WHERE tasks.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_task]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   

    public function updateTaskStatus($taskCode, $statut) {
        $query = "UPDATE tasks SET statut = :statut, matricule_status='".$_SESSION['matricule_personnel_tasks']."' WHERE task_code = :task_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':task_code', $taskCode);
        $stmt->execute();
    }

    public function getTaskExpiredByMatricule($matricule){
        $query = "SELECT * FROM tasks WHERE assigned_to = ? AND deadline > NOW() AND statut='En Attente'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$matricule]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskById($id_task){
        $query = "SELECT * FROM tasks WHERE id = ? ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_task]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkTaskHasImage($id_task){

        $response = false;

        $query = "SELECT * FROM task WHERE task_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_task]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($result['images'])>=1){
            $response = true;
        }  

        return $response;

    }

    
}
?>
