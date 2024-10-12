<?php
// ../model/ExpressionBesoinFiles.php
class BesoinExpressionFiles
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert($expressionBesoinId, $filePath, $fileType)
    {
        $sql = "INSERT INTO expression_besoin_files (expression_besoin_id, file_path, file_type)
                VALUES (:expression_besoin_id, :file_path, :file_type)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':expression_besoin_id', $expressionBesoinId);
        $stmt->bindParam(':file_path', $filePath);
        $stmt->bindParam(':file_type', $fileType);
        $stmt->execute();
    }

    public function getFilesByFicheId($expressionBesoinId)
    {
        $sql = "SELECT * FROM expression_besoin_files WHERE expression_besoin_id = :expression_besoin_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':expression_besoin_id', $expressionBesoinId);
        $stmt->execute();

        // On récupère tous les fichiers sous forme de tableau associatif
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $files; // Retourne la liste des fichiers
    }
}
