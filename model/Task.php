<?php
require_once 'Database.php';

class Task {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function create($task_code, $description, $assigned_to, $deadline, $statut, $duree, $matricule_assignateur, $projet) {
        $query = "INSERT INTO tasks (task_code, description, assigned_to, deadline, statut, duree, matricule_assignateur, projet) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$task_code, $description, $assigned_to, $deadline, $statut, $duree, $matricule_assignateur, $projet]);
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

    public function getThisMonthTasksByMatricule($matricule) {
        $query = "SELECT * FROM tasks WHERE assigned_to = ? AND 
                  MONTH(created_at) = MONTH(CURRENT_DATE()) AND 
                  YEAR(created_at) = YEAR(CURRENT_DATE())";
                  
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

    public function getTasksGroupedByStatus() {
        // Requête pour récupérer le nombre de tâches par statut
        $query = $this->conn->query("SELECT statut, COUNT(*) as total FROM tasks GROUP BY statut");
        return $query->fetchAll(PDO::FETCH_ASSOC);
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

    public function getTimeForTask($taskId) {
        $query = "SELECT duree FROM tasks WHERE id = :taskId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
   
    public function getTotalTimeByStatus($matricule, $status) {
        $query = "SELECT SUM(TIME_TO_SEC(duree)) AS total_seconds 
                  FROM tasks 
                  WHERE assigned_to = :matricule AND statut = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total_seconds'] : 0;
    }

    // Méthode pour récupérer les tâches par plage de dates
    public function getTasksByDateRange($date_debut, $date_fin) {
        // Assurez-vous que les dates sont formatées correctement pour votre requête SQL
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE created_at BETWEEN :date_debut AND :date_fin");
        $stmt->execute(['date_debut' => $date_debut, 'date_fin' => $date_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer les tâches par matricule et plage de dates
    public function getTasksByEnAttenteMatriculeAndDateRange($matricule, $date_debut, $date_fin) {
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE statut = 'En Attente' AND assigned_to = :matricule AND created_at BETWEEN :date_debut AND :date_fin");
        $stmt->execute(['matricule' => $matricule, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer les tâches par matricule et plage de dates
    public function getTasksByMatriculeAndDateRange($matricule, $date_debut, $date_fin) {
        $stmt = $this->conn->prepare("SELECT * FROM tasks WHERE assigned_to = :matricule AND created_at BETWEEN :date_debut AND :date_fin");
        $stmt->execute(['matricule' => $matricule, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      
}
?>
