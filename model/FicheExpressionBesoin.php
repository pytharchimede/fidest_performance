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
}
