<?php
// ../model/BesoinExpression.php
class BesoinExpression
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($expressionBesoinId, $type, $objet, $quantite, $fournisseur, $nomFournisseur, $prixUnitaire, $telephone)
    {
        $sql = "INSERT INTO besoin_expression (expression_besoin_id, type, objet, quantite, fournisseur, nom_fournisseur, prix_unitaire, telephone)
                VALUES (:expression_besoin_id, :type, :objet, :quantite, :fournisseur, :nom_fournisseur, :prix_unitaire, :telephone)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':expression_besoin_id', $expressionBesoinId);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':objet', $objet);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':fournisseur', $fournisseur);
        $stmt->bindParam(':nom_fournisseur', $nomFournisseur);
        $stmt->bindParam(':prix_unitaire', $prixUnitaire);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();
    }

    public function update($id, $objet, $quantite, $prixUnitaire, $fournisseur, $nomFournisseur, $telephone)
    {
        $sql = "UPDATE besoin_expression SET 
            objet = :objet, 
            quantite = :quantite, 
            prix_unitaire = :prix_unitaire, 
            fournisseur = :fournisseur, 
            nom_fournisseur = :nom_fournisseur, 
            telephone = :telephone 
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':objet', $objet);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->bindParam(':prix_unitaire', $prixUnitaire);
        $stmt->bindParam(':fournisseur', $fournisseur);
        $stmt->bindParam(':nom_fournisseur', $nomFournisseur);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();
    }


    public function getBesoinsByFicheId($expressionBesoinId)
    {
        $sql = "SELECT * FROM besoin_expression WHERE expression_besoin_id = :expression_besoin_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':expression_besoin_id', $expressionBesoinId);
        $stmt->execute();

        // On récupère tous les besoins sous forme de tableau associatif
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $besoins; // Retourne la liste des besoins
    }

    public function getBesoinsById($besoinId)
    {
        $sql = "SELECT * FROM besoin_expression WHERE id = :besoin_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':besoin_id', $besoinId);
        $stmt->execute();

        // On récupère tous les besoins sous forme de tableau associatif
        $besoin = $stmt->fetch(PDO::FETCH_ASSOC);

        return $besoin; // Vérifiez ici si 'objet' est bien rempli
    }
}
