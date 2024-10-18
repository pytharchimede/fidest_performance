<?php
class ServiceSync
{
    private $dbSoignant;
    private $dbTasks;

    public function __construct()
    {
        $this->connectDatabases();
    }

    private function connectDatabases()
    {
        // Connexion à la base de données pour service (fidestci_stock_db)
        $this->dbSoignant = $this->getConnection('fidestci_stock_db', 'fidestci_ulrich', '@Succes2019');
        // Connexion à la base de données pour service_tasks (fidestci_app_db)
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
        // Sélectionner les données de la table `service`
        $stmt = $this->dbSoignant->query("SELECT * FROM service");
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($services as $service) {
            // Vérifier si le service existe déjà dans `service_tasks`
            $stmt = $this->dbTasks->prepare("SELECT COUNT(*) FROM service_tasks WHERE id_service_tasks = :id");
            $stmt->execute(['id' => $service['id_service']]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                // Mettre à jour l'enregistrement existant
                $this->updateServiceTasks($service);
            } else {
                // Insérer un nouvel enregistrement
                $this->insertServiceTasks($service);
            }
        }
    }

    private function updateServiceTasks($service)
    {
        $stmt = $this->dbTasks->prepare("UPDATE service_tasks SET
            lib_service_tasks = :libelle,
            date_creat = :date_creat,
            secur_ajout = :secur
            WHERE id_service_tasks = :id");

        $stmt->execute([
            'id' => $service['id_service'],
            'libelle' => $service['lib_service'],
            'date_creat' => $service['date_creat'],
            'secur' => $service['secur_ajout']
        ]);
    }

    private function insertServiceTasks($service)
    {
        $stmt = $this->dbTasks->prepare("INSERT INTO service_tasks (id_service_tasks, lib_service_tasks, date_creat, secur_ajout) VALUES
        (:id, :libelle, :date_creat, :secur)");

        $stmt->execute([
            'id' => $service['id_service'],
            'libelle' => $service['lib_service'],
            'date_creat' => $service['date_creat'],
            'secur' => $service['secur_ajout']
        ]);
    }

    public function run()
    {
        $this->synchronizeData();
    }
}

// Exécution de la synchronisation
$synchronizer = new ServiceSync();
$synchronizer->run();
