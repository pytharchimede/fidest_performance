<?php
session_start();
// Inclure la connexion à la base de données
require_once '../model/Database.php';
require_once '../model/Personnel.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];

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

        // Rediriger vers la page protégée
        header("Location: ../dashboard.php");
        exit();
    } else {
        // Matricule incorrect, rediriger vers une page d'erreur avec message
        $_SESSION['error_message'] = "Numéro matricule incorrect.";
        header("Location: ../page_error.php");
        exit();
    }
}
?>
