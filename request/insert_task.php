<?php
session_start();
// Inclure la connexion à la base de données
require_once '../model/Database.php';
require_once '../model/Personnel.php'; // Inclure la classe Personnel
require_once '../model/Task.php'; // Inclure la classe Personnel
require_once '../../OrangeSMS.php';
require_once '../model/TracabilitePerformance.php'; // Inclure la classe TracabilitePerformance

if (!isset($_SESSION['id_personnel_tasks'])) {
    header("Location: ../index.php");    exit();
}

if($_SESSION['role']!='superviseur'){
    header('Location: ../acces_refuse.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = Database::getConnection();

        //$taskCode = $_POST['taskCode'];
        $taskDescription = $_POST['taskDescription'];
        $assignedTo = $_POST['assignedTo'];
        $deadline = $_POST['deadline'];
        $statut = 'En Attente';
        $duree = $_POST['duree'];
        $matricule_assignateur = $_POST['matricule_assignateur'];
        $projet = $_POST['projet'];


        // Convertir la durée en secondes
        $dureeExploded = explode(':', $duree); // Séparer les heures, minutes et secondes
        $heures = isset($dureeExploded[0]) ? (int)$dureeExploded[0] : 0;
        $minutes = isset($dureeExploded[1]) ? (int)$dureeExploded[1] : 0;
        $secondes = isset($dureeExploded[2]) ? (int)$dureeExploded[2] : 0;

        // Calcul de la durée totale en secondes
        $dureeEnSecondes = ($heures * 3600) + ($minutes * 60) + $secondes;

        $task = new Task();
        $nbTask = count($task->getAllTasks());
        $indice = $nbTask+1;
        $taskCode = 'FID/TSK/'.$indice;

        // Gestion des fichiers
        $images = [];
        if (isset($_FILES['taskImage']) && is_array($_FILES['taskImage']['name'])) {
            foreach ($_FILES['taskImage']['name'] as $key => $name) {
                if ($_FILES['taskImage']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['taskImage']['tmp_name'][$key];
                    $newName = uniqid() . '-' . basename($name);
                    $uploadPath = __DIR__ . '/uploads/' . $newName;

                    if (move_uploaded_file($tmp_name, $uploadPath)) {
                        $images[] = $newName;
                    } else {
                        error_log("Erreur lors du déplacement du fichier $name à $uploadPath");
                        //throw new Exception("Erreur lors du déplacement du fichier $name");
                    }
                } else {
                    //error_log("Erreur de téléchargement pour le fichier $name : " . $_FILES['taskImage']['error'][$key]);
                    //throw new Exception("Erreur lors du téléchargement du fichier $name");
                }
            }
        } else {
            error_log("Aucun fichier téléchargé ou le champ 'taskImage' est incorrect.");
        }

        // Insérer les données dans la base de données
        $stmt = $pdo->prepare('INSERT INTO tasks (task_code, description, assigned_to, deadline, images, statut, duree, matricule_assignateur, dureeEnSecondes, projet) VALUES (:taskCode, :description, :assignedTo, :deadline, :images, :statut, :duree, :matricule_assignateur, :dureeEnSecondes, :projet)');
        $stmt->execute([
            ':taskCode' => $taskCode,
            ':description' => $taskDescription,
            ':assignedTo' => $assignedTo,
            ':deadline' => $deadline,
            ':images' => json_encode($images), // Convertir le tableau des images en JSON
            ':statut' => $statut,
            ':duree' => $duree,
            ':matricule_assignateur' => $matricule_assignateur,
            ':dureeEnSecondes' => $dureeEnSecondes,
            ':projet' => $projet
        ]);

        // Ajout de la traçabilité
        
        $tracabilite = new TracabilitePerformance($pdo); // Instancier la classe Tracabilite
        $libelle = "Ajout d'une nouvelle tâche : $taskCode, assignée à $assignedTo";
        $tracabilite->enregistrerAction($libelle); // Enregistrer l'action de traçabilité
        
       // echo $stmt;

                 //Envoi SMS Personnel
        
/*

                   // Récupérer le numéro de téléphone du personnel assigné
        $personnelObj = new Personnel();
        $tel_personnel_tasks = $personnelObj->getPhoneByMatricule($assignedTo);
        
            // Importation de la classe
$clientId = 'Xb6Wgzi9iCWFAJakdSNPCpMGBx9ixxF0';
$clientSecret = 'xOXZ4QTDf7bLfGk3';

    // Instanciation de la classe OrangeSMS
    $orangeSMS = new OrangeSMS($clientId, $clientSecret);
    $telephone_beneficiaire=$tel_personnel_tasks;

    // Format du numéro de téléphone sans ajouter de 'tel:' supplémentaire
    $recipientPhoneNumber = '+225' . $telephone_beneficiaire;
    $senderPhoneNumber = '+2250748367710';

    // Envoi d'un SMS
    $message = '
        Une tâche vous a été asssignée. cliquez sur https://fidest.ci/performance/ pour plus de détails. 
    ';
    $response = $orangeSMS->sendSMS('tel:' . $recipientPhoneNumber, 'tel:' . $senderPhoneNumber, $message);
    print_r($response);

        */
        //Fin SMS Personnel


        //echo 'Tâche ajoutée avec succès !'; //tel_personnel_tasks
 
       // header('Location: ../taches_en_attente.php');


    } catch (Exception $e) {
        error_log('Erreur : ' . $e->getMessage());
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    echo 'Méthode HTTP non autorisée';
}
?>
