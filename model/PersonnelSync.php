<?php
class PersonnelSync {
    private $dbSoignant;
    private $dbTasks;

    public function __construct() {
        $this->connectDatabases();
    }

    private function connectDatabases() {
        // Connexion à la base de données pour personnel_soignant
        $this->dbSoignant = $this->getConnection('fidestci_stock_db', 'fidestci_ulrich', '@Succes2019');
        // Connexion à la base de données pour personnel_tasks
        $this->dbTasks = $this->getConnection('fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    }

    private function getConnection($dbname, $username, $password) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function synchronizeData() {
        $stmt = $this->dbSoignant->query("SELECT * FROM personnel_soignant");
        $personnelSoignant = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($personnelSoignant as $personnel) {
            // Vérifier si le personnel existe déjà dans personnel_tasks
            $stmt = $this->dbTasks->prepare("SELECT COUNT(*) FROM personnel_tasks WHERE matricule_personnel_tasks = :matricule");
            $stmt->execute(['matricule' => $personnel['matricule_personnel_soignant']]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Mettre à jour l'enregistrement existant
                $this->updatePersonnelTasks($personnel);
            } else {
                // Insérer un nouvel enregistrement
                $this->insertPersonnelTasks($personnel);
            }
        }
    }

    private function updatePersonnelTasks($personnel) {
        $stmt = $this->dbTasks->prepare("UPDATE personnel_tasks SET
            nom_personnel_tasks = :nom,
            sexe_personnel_tasks = :sexe,
            date_nais_personnel_tasks = :date_nais,
            tel_personnel_tasks = :tel,
            email_personnel_tasks = :email,
            photo_personnel_tasks = :photo,
            date_recrutement = :date_recrutement,
            service_id = :service_id,
            fonction_id = :fonction_id,
            statut_pers_soignant_code = :statut,
            date_creat_pers_soign = :date_creat,
            secur_ajout = :secur,
            valide = :valide,
            motif_sortie_pers_soign_id = :motif_sortie,
            date_sortie = :date_sortie,
            date_creat_sortie = :date_creat_sortie,
            site_id = :site_id,
            situation_matrimoniale = :situation_matrimoniale,
            nom_pere = :nom_pere,
            tel_pere = :tel_pere,
            num_cni = :num_cni,
            nbre_enfant = :nbre_enfant,
            num_cnps = :num_cnps,
            nom_mere = :nom_mere,
            tel_mere = :tel_mere,
            nom_personne_urgence = :nom_personne_urgence,
            tel_personne_urgence = :tel_personne_urgence
            WHERE matricule_personnel_tasks = :matricule");

        $stmt->execute([
            'matricule' => $personnel['matricule_personnel_soignant'],
            'nom' => $personnel['nom_personnel_soignant'],
            'sexe' => $personnel['sexe_personnel_soignant'],
            'date_nais' => $personnel['date_nais_personnel_soignant'],
            'tel' => $personnel['tel_personnel_soignant'],
            'email' => $personnel['email_personnel_soignant'],
            'photo' => $personnel['photo_personnel_soignant'],
            'date_recrutement' => $personnel['date_recrutement'],
            'service_id' => $personnel['service_id'],
            'fonction_id' => $personnel['fonction_id'],
            'statut' => $personnel['statut_pers_soignant_code'],
            'date_creat' => $personnel['date_creat_pers_soign'],
            'secur' => $personnel['secur_ajout'],
            'valide' => $personnel['valide'],
            'motif_sortie' => $personnel['motif_sortie_pers_soign_id'],
            'date_sortie' => $personnel['date_sortie'],
            'date_creat_sortie' => $personnel['date_creat_sortie'],
            'site_id' => $personnel['site_id'],
            'situation_matrimoniale' => $personnel['situation_matrimoniale'],
            'nom_pere' => $personnel['nom_pere'],
            'tel_pere' => $personnel['tel_pere'],
            'num_cni' => $personnel['num_cni'],
            'nbre_enfant' => $personnel['nbre_enfant'],
            'num_cnps' => $personnel['num_cnps'],
            'nom_mere' => $personnel['nom_mere'],
            'tel_mere' => $personnel['tel_mere'],
            'nom_personne_urgence' => $personnel['nom_personne_urgence'],
            'tel_personne_urgence' => $personnel['tel_personne_urgence'],
        ]);
    }

    private function insertPersonnelTasks($personnel) {
        $stmt = $this->dbTasks->prepare("INSERT INTO personnel_tasks (matricule_personnel_tasks, nom_personnel_tasks, sexe_personnel_tasks, date_nais_personnel_tasks, tel_personnel_tasks, email_personnel_tasks, photo_personnel_tasks, date_recrutement, service_id, fonction_id, statut_pers_soignant_code, date_creat_pers_soign, secur_ajout, valide, motif_sortie_pers_soign_id, date_sortie, date_creat_sortie, site_id, situation_matrimoniale, nom_pere, tel_pere, num_cni, nbre_enfant, num_cnps, nom_mere, tel_mere, nom_personne_urgence, tel_personne_urgence) VALUES
        (:matricule, :nom, :sexe, :date_nais, :tel, :email, :photo, :date_recrutement, :service_id, :fonction_id, :statut, :date_creat, :secur, :valide, :motif_sortie, :date_sortie, :date_creat_sortie, :site_id, :situation_matrimoniale, :nom_pere, :tel_pere, :num_cni, :nbre_enfant, :num_cnps, :nom_mere, :tel_mere, :nom_personne_urgence, :tel_personne_urgence)");

        $stmt->execute([
            'matricule' => $personnel['matricule_personnel_soignant'],
            'nom' => $personnel['nom_personnel_soignant'],
            'sexe' => $personnel['sexe_personnel_soignant'],
            'date_nais' => $personnel['date_nais_personnel_soignant'],
            'tel' => $personnel['tel_personnel_soignant'],
            'email' => $personnel['email_personnel_soignant'],
            'photo' => $personnel['photo_personnel_soignant'],
            'date_recrutement' => $personnel['date_recrutement'],
            'service_id' => $personnel['service_id'],
            'fonction_id' => $personnel['fonction_id'],
            'statut' => $personnel['statut_pers_soignant_code'],
            'date_creat' => $personnel['date_creat_pers_soign'],
            'secur' => $personnel['secur_ajout'],
            'valide' => $personnel['valide'],
            'motif_sortie' => $personnel['motif_sortie_pers_soign_id'],
            'date_sortie' => $personnel['date_sortie'],
            'date_creat_sortie' => $personnel['date_creat_sortie'],
            'site_id' => $personnel['site_id'],
            'situation_matrimoniale' => $personnel['situation_matrimoniale'],
            'nom_pere' => $personnel['nom_pere'],
            'tel_pere' => $personnel['tel_pere'],
            'num_cni' => $personnel['num_cni'],
            'nbre_enfant' => $personnel['nbre_enfant'],
            'num_cnps' => $personnel['num_cnps'],
            'nom_mere' => $personnel['nom_mere'],
            'tel_mere' => $personnel['tel_mere'],
            'nom_personne_urgence' => $personnel['nom_personne_urgence'],
            'tel_personne_urgence' => $personnel['tel_personne_urgence'],
        ]);
    }

    public function run() {
        $this->synchronizeData();
    }
}

// Exécution de la synchronisation
$synchronizer = new PersonnelSync();
$synchronizer->run();


