<?php
// ../model/FicheExpressionBesoin.php
class FicheExpressionBesoin
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($nom, $matricule, $departement, $description, $montant, $date, $impact, $frequence)
    {
        $sql = "INSERT INTO fiche_expression_besoin (nom, matricule, departement, description, montant, date, impact, frequence)
                VALUES (:nom, :matricule, :departement, :description, :montant, :date, :impact, :frequence)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':matricule', $matricule);
        $stmt->bindParam(':departement', $departement);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':montant', $montant);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':impact', $impact);
        $stmt->bindParam(':frequence', $frequence);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    // Méthode pour lister toutes les fiches
    public function listerFiches()
    {
        try {
            // Préparation de la requête SQL pour récupérer toutes les fiches
            $sql = "SELECT * FROM fiche_expression_besoin ORDER BY date DESC";
            $stmt = $this->pdo->prepare($sql);

            // Exécution de la requête
            $stmt->execute();

            // Récupération des résultats sous forme de tableau associatif
            $fiches = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérification si des fiches sont trouvées
            if ($fiches) {
                return $fiches;
            } else {
                return [];  // Retourne un tableau vide si aucune fiche n'est trouvée
            }
        } catch (Exception $e) {
            // Gestion des erreurs
            return 'Erreur : ' . $e->getMessage();
        }
    }

    // Méthode pour obtenir une fiche par son ID
    public function obtenirFicheParId($id)
    {
        try {
            // Préparation de la requête SQL pour récupérer la fiche par ID
            $sql = "SELECT * FROM fiche_expression_besoin WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat
            $fiche = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification si la fiche est trouvée
            if ($fiche) {
                return $fiche;
            } else {
                return null; // Retourne null si aucune fiche n'est trouvée
            }
        } catch (Exception $e) {
            // Gestion des erreurs
            return 'Erreur : ' . $e->getMessage();
        }
    }
}
