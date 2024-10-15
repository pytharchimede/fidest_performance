<?php
session_start();
// Inclure la connexion à la base de données
require_once '../model/Database.php';
require_once '../model/Personnel.php';
require_once '../model/TracabilitePerformance.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $password = $_POST['password'] ? $_POST['password'] : ''; // Obtenir le mot de passe du formulaire

    // Obtenir la connexion PDO
    $pdo = Database::getConnection();

    // Vérifier si le matricule existe
    $sql = "SELECT * FROM personnel_tasks WHERE matricule_personnel_tasks = :matricule";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->execute();

    // Vérifier si des résultats existent
    if ($stmt->rowCount() > 0) {
        // Matricule valide, récupérer les données de l'utilisateur
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Déclarer les variables de session pour chaque champ
        $_SESSION['id_personnel_tasks'] = $row['id_personnel_tasks'];
        $_SESSION['matricule_personnel_tasks'] = $row['matricule_personnel_tasks'];
        $_SESSION['nom_personnel_tasks'] = $row['nom_personnel_tasks'];
        $_SESSION['sexe_personnel_tasks'] = $row['sexe_personnel_tasks'];
        $_SESSION['date_nais_personnel_tasks'] = $row['date_nais_personnel_tasks'];
        $_SESSION['tel_personnel_tasks'] = $row['tel_personnel_tasks'];
        $_SESSION['email_personnel_tasks'] = $row['email_personnel_tasks'];
        $_SESSION['photo_personnel_tasks'] = $row['photo_personnel_tasks'];
        $_SESSION['date_recrutement'] = $row['date_recrutement'];
        $_SESSION['service_id'] = $row['service_id'];
        $_SESSION['fonction_id'] = $row['fonction_id'];
        $_SESSION['statut_pers_soignant_code'] = $row['statut_pers_soignant_code'];
        $_SESSION['date_creat_pers_soign'] = $row['date_creat_pers_soign'];
        $_SESSION['secur_ajout'] = $row['secur_ajout'];
        $_SESSION['valide'] = $row['valide'];
        $_SESSION['motif_sortie_pers_soign_id'] = $row['motif_sortie_pers_soign_id'];
        $_SESSION['date_sortie'] = $row['date_sortie'];
        $_SESSION['date_creat_sortie'] = $row['date_creat_sortie'];
        $_SESSION['site_id'] = $row['site_id'];
        $_SESSION['situation_matrimoniale'] = $row['situation_matrimoniale'];
        $_SESSION['nom_pere'] = $row['nom_pere'];
        $_SESSION['tel_pere'] = $row['tel_pere'];
        $_SESSION['num_cni'] = $row['num_cni'];
        $_SESSION['nbre_enfant'] = $row['nbre_enfant'];
        $_SESSION['num_cnps'] = $row['num_cnps'];
        $_SESSION['nom_mere'] = $row['nom_mere'];
        $_SESSION['tel_mere'] = $row['tel_mere'];
        $_SESSION['nom_personne_urgence'] = $row['nom_personne_urgence'];
        $_SESSION['tel_personne_urgence'] = $row['tel_personne_urgence'];
        $_SESSION['salaire_mensuel_personnel_tasks'] = $row['salaire_mensuel_personnel_tasks'];
        $_SESSION['avance_deductible'] = $row['avance_deductible'];
        $_SESSION['jours_travailles'] = $row['jours_travailles'];
        $_SESSION['score'] = $row['score'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['acces_rh'] = $row['acces_rh'];
        $_SESSION['password_personnel_tasks'] = $row['password_personnel_tasks'];
        $_SESSION['is_directeur'] = $row['is_directeur'];
        $_SESSION['valid_besoin'] = $row['valid_besoin'];
        $_SESSION['acces_pret'] = $row['acces_pret'];
        $_SESSION['acces_avance'] = $row['acces_avance'];
        $_SESSION['acces_absence'] = $row['acces_absence'];
        $_SESSION['acces_besoin'] = $row['acces_besoin'];
        $_SESSION['nombre_connection'] = $row['nombre_connection'];


        //Compter le nombre de connections
        $nbreConnection = $row['nombre_connection'];
        $newNombreConnection = $nbreConnection++;

        //Mettre à jour le nombre de connections
        $sqlConnection = "UPDATE personnel_tasks SET nombre_connection = :new_nombre_connection";
        $stmtConnection = $pdo->prepare($sqlConnection);
        $stmtConnection->bindParam(':newNombreConnection', $newNombreConnection, PDO::PARAM_STR);
        $stmtConnection->execute();





        // Si l'email, téléphone ou mot de passe ne sont pas définis, rediriger vers la page de mise à jour
        if (empty($row['email_personnel_tasks']) || empty($row['tel_personnel_tasks']) || empty($row['password_personnel_tasks'])) {
            header("Location: ../update_contact_info.php");
            exit();
        }

        // Activer la demande de mot de passe
        $_SESSION['demande_password'] = true;  // Nouveau code ajouté

        // Vérification du mot de passe
        $hashedPassword = hash("sha512", $password); // Hacher le mot de passe fourni
        if ($hashedPassword === $row['password_personnel_tasks']) {
            // Connexion réussie

            // Ajout de la traçabilité
            $tracabilite = new TracabilitePerformance($pdo); // Instancier la classe Tracabilite
            $libelle = "Connexion de " . $_SESSION['nom_personnel_tasks'] . " ";
            $tracabilite->enregistrerAction($libelle); // Enregistrer l'action de traçabilité

            // Redirection vers le tableau de bord
            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            header("Location: ../login.php");
            exit();
        }
    } else {
        // Matricule incorrect, rediriger vers une page d'erreur avec message
        $_SESSION['error_message'] = "Numéro matricule incorrect.";
        header("Location: ../page_error.php");
        exit();
    }
}
