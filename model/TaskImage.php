<?php
require_once 'Database.php';

class TaskImage {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function upload($task_id, $image_path) {
        $query = "INSERT INTO task_images (task_id, image_path) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$task_id, $image_path]);
    }

    public function getByTaskId($task_id) {
        $query = "SELECT * FROM task_images WHERE task_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
