<?php
class FonctionSync
{
    private $dbSoignant;
    private $dbTasks;

    public function __construct()
    {
        $this->connectDatabases();
    }

    private function connectDatabases()
    {
        // Connexion à la base de données pour fonction (personnel_soignant)
        $this->dbSoignant = $this->getConnection('fidestci_stock_db', 'fidestci_ulrich', '@Succes2019');
        // Connexion à la base de données pour fonction_tasks
        $this->dbTasks = $this->getConnection('fidestci_app_db', 'fidestci_ulrich', '@Succes2019');
    }

    private function getConnection($dbname, $username, $password)
    {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function synchronizeData()
    {
        $stmt = $this->dbSoignant->query("SELECT * FROM fonction");
        $fonctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($fonctions as $fonction) {
            // Vérifier si la fonction existe déjà dans fonction_tasks
            $stmt = $this->dbTasks->prepare("SELECT COUNT(*) FROM fonction_tasks WHERE id_fonction_tasks = :id");
            $stmt->execute(['id' => $fonction['id_fonction']]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Mettre à jour l'enregistrement existant
                $this->updateFonctionTasks($fonction);
            } else {
                // Insérer un nouvel enregistrement
                $this->insertFonctionTasks($fonction);
            }
        }
    }

    private function updateFonctionTasks($fonction)
    {
        $stmt = $this->dbTasks->prepare("UPDATE fonction_tasks SET
            lib_fonction_tasks = :libelle,
            date_creat = :date_creat,
            secur_ajout = :secur
            WHERE id_fonction_tasks = :id");

        $stmt->execute([
            'id' => $fonction['id_fonction'],
            'libelle' => $fonction['lib_fonction'],
            'date_creat' => $fonction['date_creat'],
            'secur' => $fonction['secur_ajout']
        ]);
    }

    private function insertFonctionTasks($fonction)
    {
        $stmt = $this->dbTasks->prepare("INSERT INTO fonction_tasks (id_fonction_tasks, lib_fonction_tasks, date_creat, secur_ajout) VALUES
        (:id, :libelle, :date_creat, :secur)");

        $stmt->execute([
            'id' => $fonction['id_fonction'],
            'libelle' => $fonction['lib_fonction'],
            'date_creat' => $fonction['date_creat'],
            'secur' => $fonction['secur_ajout']
        ]);
    }

    public function run()
    {
        $this->synchronizeData();
    }
}

// Exécution de la synchronisation
$synchronizer = new FonctionSync();
$synchronizer->run();
