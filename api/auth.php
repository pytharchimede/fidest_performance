<?php
header('Content-Type: application/json');
session_start();
require_once '../model/Database.php';
require_once '../model/Personnel.php';

$response = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $matricule = $data['matricule'];
    $password = $data['password'];

    // Vérification de l'utilisateur dans la base de données
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT * FROM personnel_tasks WHERE matricule_personnel_tasks = :matricule");
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = hash("sha512", $password);

        if ($hashedPassword === $row['password_personnel_tasks']) {
            // Renvoyer les données utilisateur sous forme JSON
            $response['success'] = true;
            $response['user'] = $row;
        } else {
            $response['success'] = false;
            $response['message'] = "Mot de passe incorrect.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Numéro matricule incorrect.";
    }
}
echo json_encode($response);
