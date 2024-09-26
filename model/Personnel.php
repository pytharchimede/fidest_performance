<?php

require_once 'Database.php';
require_once 'Helper.php';


class Personnel{
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Ajouter un nouveau membre du personnel
    public function ajouterPersonnel(
        $matricule, 
        $nom, 
        $sexe, 
        $dateNais, 
        $tel, 
        $email, 
        $photo, 
        $dateRecrutement, 
        $serviceId, 
        $fonctionId, 
        $statutPersSoignant, 
        $securAjout, 
        $valide, 
        $siteId, 
        $situationMatrimoniale, 
        $nomPere, 
        $telPere, 
        $numCni, 
        $nbreEnfant, 
        $numCnps, 
        $nomMere, 
        $telMere, 
        $nomUrgence, 
        $telUrgence
        ) {
        $sql = "INSERT INTO personnel_tasks (matricule_personnel_tasks, 
            nom_personnel_tasks, 
            sexe_personnel_tasks, 
            date_nais_personnel_tasks, 
            tel_personnel_tasks, 
            email_personnel_tasks, 
            photo_personnel_tasks, 
            date_recrutement, 
            service_id, 
            fonction_id, 
            statut_pers_soignant_code, 
            secur_ajout, 
            valide, 
            site_id, 
            situation_matrimoniale, 
            nom_pere, 
            tel_pere, 
            num_cni, 
            nbre_enfant, 
            num_cnps, 
            nom_mere, 
            tel_mere, 
            nom_personne_urgence, 
            tel_personne_urgence
        ) VALUES (
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?
        )";
        
        $stmt = $this->pdo->prepare($sql);
    
        // Assurez-vous que le nombre de variables correspond au nombre de placeholders
        return $stmt->execute([
            $matricule, 
            $nom, 
            $sexe, 
            $dateNais, 
            $tel, 
            $email, 
            $photo, 
            $dateRecrutement, 
            $serviceId, 
            $fonctionId, 
            $statutPersSoignant, 
            $securAjout, 
            $valide, 
            $siteId, 
            $situationMatrimoniale, 
            $nomPere, 
            $telPere, 
            $numCni, 
            $nbreEnfant, 
            $numCnps, 
            $nomMere, 
            $telMere, 
            $nomUrgence, 
            $telUrgence
        ]);
    }


        // Ajouter un nouveau membre du personnel
        public function ajouterPersonnelTask(
            $matricule, 
            $nom, 
            $sexe, 
            $tel, 
            $email,
            $salaire
            ) {
            $sql = "INSERT INTO personnel_tasks (
                matricule_personnel_tasks, 
                nom_personnel_tasks, 
                sexe_personnel_tasks, 
                tel_personnel_tasks, 
                email_personnel_tasks,
                salaire_mensuel_personnel_tasks
            ) VALUES (
                ?, 
                ?, 
                ?, 
                ?, 
                ?,
                ?
            )";
            
            $stmt = $this->pdo->prepare($sql);
        
            // Assurez-vous que le nombre de variables correspond au nombre de placeholders
            return $stmt->execute([
                $matricule, 
                $nom, 
                $sexe, 
                $tel, 
                $email,
                $salaire 
            ]);
        }
        

        //Mise à jour du personnel
        public function mettreAJourPersonnel(
            $id, 
            $matricule, 
            $nom, 
            $sexe, 
            $telephone, 
            $email, 
            $salaire
        ) {
            $sql = "UPDATE personnel_tasks SET
                matricule_personnel_tasks = ?,
                nom_personnel_tasks = ?,
                sexe_personnel_tasks = ?,
                tel_personnel_tasks = ?,
                email_personnel_tasks = ?,
                salaire_mensuel_personnel_tasks = ?
            WHERE id_personnel_tasks = ?";
            
            $stmt = $this->pdo->prepare($sql);
        
            if (!$stmt->execute([
                $matricule, 
                $nom, 
                $sexe, 
                $telephone, 
                $email,
                $salaire,
                $id
            ])) {
                // Afficher les erreurs PDO si la requête échoue
                $errorInfo = $stmt->errorInfo();
                echo "Erreur SQL : " . $errorInfo[2];
                return false;
            }
        
            return true;
        }
        
        
    
    
    

    // Modifier les informations d'un membre du personnel
    public function modifierPersonnel($id, $matricule, $nom, $sexe, $dateNais, $tel, $email, $photo, $dateRecrutement, $serviceId, $fonctionId, $statutPersSoignant, $securAjout, $valide, $siteId, $situationMatrimoniale, $nomPere, $telPere, $numCni, $nbreEnfant, $numCnps, $nomMere, $telMere, $nomUrgence, $telUrgence) {
        $sql = "UPDATE personnel_tasks SET matricule_personnel_tasks = ?, nom_personnel_tasks = ?, sexe_personnel_tasks = ?, date_nais_personnel_tasks = ?, tel_personnel_tasks = ?, email_personnel_tasks = ?, photo_personnel_tasks = ?, date_recrutement = ?, service_id = ?, fonction_id = ?, statut_pers_soignant_code = ?, secur_ajout = ?, valide = ?, site_id = ?, situation_matrimoniale = ?, nom_pere = ?, tel_pere = ?, num_cni = ?, nbre_enfant = ?, num_cnps = ?, nom_mere = ?, tel_mere = ?, nom_personne_urgence = ?, tel_personne_urgence = ? WHERE id_personnel_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$matricule, $nom, $sexe, $dateNais, $tel, $email, $photo, $dateRecrutement, $serviceId, $fonctionId, $statutPersSoignant, $securAjout, $valide, $siteId, $situationMatrimoniale, $nomPere, $telPere, $numCni, $nbreEnfant, $numCnps, $nomMere, $telMere, $nomUrgence, $telUrgence, $id]);
    }

    // Supprimer un membre du personnel
    public function supprimerPersonnel($id) {
        $sql = "DELETE FROM personnel_tasks WHERE id_personnel_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lister tous les membres du personnel
    public function listerPersonnel() {
        $sql = "SELECT * FROM personnel_tasks";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtenir les détails d'un membre du personnel
    public function obtenirPersonnelParId($id) {
        $sql = "SELECT * FROM personnel_tasks WHERE id_personnel_tasks = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     // Méthode pour récupérer le numéro de téléphone à partir du matricule
     public function getPhoneByMatricule($matricule) {
        $stmt = $this->pdo->prepare('SELECT tel_personnel_tasks FROM personnel_tasks WHERE matricule_personnel_tasks = :matricule');
        $stmt->execute([':matricule' => $matricule]);
        return $stmt->fetchColumn(); // Récupère le numéro de téléphone
    }

         // Méthode pour récupérer le numéro de téléphone à partir du matricule
         public function getPersonnelByMatricule($matricule) {
            $stmt = $this->pdo->prepare('SELECT * FROM personnel_tasks WHERE matricule_personnel_tasks = :matricule');
            $stmt->execute([':matricule' => $matricule]);
            return $stmt->fetchColumn(); // Récupère le numéro de téléphone
        }


    //Méthode pour récupérer le personnel par id
    public function getPersonnelById($id){
        $stmt = $this->pdo->prepare('SELECT * FROM personnel_tasks WHERE id_personnel_tasks = :id ORDER BY nom_personnel_tasks ASC ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(); // Récupère le numéro de téléphone
    }

    //Méthode pour obtenir le salaire d'un employé
    public function getSalaireByPersonnelId($personnelId) {
        $query = "SELECT salaire_mensuel_personnel_tasks
                  FROM personnel_tasks 
                  WHERE id_personnel_tasks = :personnelId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':personnelId', $personnelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Méthode pour obtenir le total des avances
    public function getAvanceByPersonnelId($personnelId) {
        $query = "SELECT avance_deductible
                  FROM personnel_tasks 
                  WHERE id_personnel_tasks = :personnelId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':personnelId', $personnelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Méthode pour obtenir le total des avances
    public function getPresenceByPersonnelId($personnelId) {
        $query = "SELECT jours_travailles
                  FROM personnel_tasks 
                  WHERE id_personnel_tasks = :personnelId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':personnelId', $personnelId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        /**
     * Vérifie si un personnel a déjà pointé pour une date donnée.
     *
     * @param int $personnelId L'identifiant du personnel.
     * @param string $datePointage La date du pointage.
     * @return array|null Les informations du pointage si elles existent, sinon null.
     */
    public function verifierPointageDuJour($personnelId, $datePointage) {
        $sql = "SELECT * FROM pointage_personnel WHERE personnel_tasks_id = :personnelId AND date_pointage = :datePointage";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':personnelId', $personnelId, PDO::PARAM_INT);
        $stmt->bindParam(':datePointage', $datePointage);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


        /**
     * Enregistre la présence d'un personnel.
     *
     * @param int $personnelId L'identifiant du personnel.
     * @param string $datePointage La date du pointage.
     * @param string $heurePointage L'heure du pointage.
     * @param bool $present Indique si le personnel est présent.
     */
    public function enregistrerPresence($id_personnel, $date_pointage, $heure_pointage, $present) {
        $stmt = $this->pdo->prepare("INSERT INTO pointage_personnel (personnel_tasks_id, date_pointage, heure_pointage, present) VALUES (:id_personnel, :date_pointage, :heure_pointage, :present)");
        $stmt->bindParam(':id_personnel', $id_personnel);
        $stmt->bindParam(':date_pointage', $date_pointage);
        $stmt->bindParam(':heure_pointage', $heure_pointage);
        $stmt->bindParam(':present', $present);
        $stmt->execute();
    }

    public function mettreAJourPresence($id_personnel, $date_pointage, $heure_pointage, $present) {
        $stmt = $this->pdo->prepare("UPDATE pointage_personnel SET heure_pointage = :heure_pointage, present = :present WHERE personnel_tasks_id = :id_personnel AND date_pointage = :date_pointage");
        $stmt->bindParam(':id_personnel', $id_personnel);
        $stmt->bindParam(':date_pointage', $date_pointage);
        $stmt->bindParam(':heure_pointage', $heure_pointage);
        $stmt->bindParam(':present', $present);
        $stmt->execute();
    }
    

        /**
     * Vérifie le pointage du jour pour tous les personnels et trie les présents en bas.
     *
     * @param string $datePointage La date du pointage.
     * @return array Un tableau contenant les identifiants des personnels ayant pointé, triés.
     */
    public function verifierPointageDuJourPourToutLeMonde($datePointage) {
        $sql = "SELECT personnel_tasks_id 
                FROM pointage_personnel 
                WHERE date_pointage = :datePointage
                ORDER BY present DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':datePointage', $datePointage);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    
    public function getRoleById($id) {
        $pdo = Database::getConnection();
        $sql = "SELECT role FROM personnel_tasks WHERE id_personnel_tasks = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    // Dans Personnel.php
    public function getAllPersonnelWithScores() {
        $query = "SELECT * FROM personnel_tasks";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $personnels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $helper = new Helper();
    
        foreach ($personnels as &$personnel) {  // Utiliser une référence pour modifier l'élément directement
            // Récupérer toutes les tâches du personnel avec leur durée en secondes et statut
            $tasks = (new Task())->getTasksByMatricule($personnel['matricule_personnel_tasks']);
            
            $totalAssignedDuration = 0;  // Temps de travail total assigné (toutes les tâches en secondes)
            $totalCompletedDuration = 0; // Temps de travail terminé (tâches terminées uniquement en secondes)
    
            foreach ($tasks as $task) {
                // Ajouter la durée de la tâche au total assigné
                $totalAssignedDuration += (int)$task['dureeEnSecondes']; // Utiliser dureeEnSecondes directement
    
                // Si la tâche est terminée, ajouter la durée au total terminé
                if ($task['statut'] === 'Termine') { // Assurez-vous que le champ de statut est correct
                    $totalCompletedDuration += (int)$task['dureeEnSecondes'];
                }
            }
    
            // Calcul du pourcentage de travail terminé
            $score = $helper->calculerScore($totalCompletedDuration, $totalAssignedDuration);
            $personnel['score'] = $score;
        }
    
        return $personnels;
    }
    
    
    
    

    public function getPointagesEntreDates($dateDebut, $dateFin) {
        $query = "SELECT id_personnel, nom_personnel, date_pointage, statut FROM pointages WHERE date_pointage BETWEEN :dateDebut AND :dateFin";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
    

}
