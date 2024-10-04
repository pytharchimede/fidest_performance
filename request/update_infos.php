<?php
session_start();
require_once '../model/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $password = $_POST['password'];

    // Vérifier si toutes les données sont présentes
    if (!empty($email) && !empty($tel) && !empty($password)) {
        // Hashage du mot de passe
        $hashedPassword= hash("sha512", $password);


        // Connexion à la base de données
        $pdo = Database::getConnection();

        // Préparer la requête SQL pour mettre à jour les informations de l'utilisateur
        $sql = "UPDATE personnel_tasks 
                SET email_personnel_tasks = :email, 
                    tel_personnel_tasks = :tel, 
                    password_personnel_tasks = :password 
                WHERE id_personnel_tasks = :id";

        $stmt = $pdo->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $_SESSION['id_personnel_tasks']);

        // Exécuter la requête
        if ($stmt->execute()) {
            //echo "Mise à jour effectuée avec succès.";
            // Rediriger vers une page de succès
            header("Location: ../success_update_contact_infos.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour des informations.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
} else {
    // Rediriger si l'accès est fait directement à la page
    header("Location: index.php");
    exit();
}
