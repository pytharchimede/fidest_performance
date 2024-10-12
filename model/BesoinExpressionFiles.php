<?php
// ../model/ExpressionBesoinFiles.php
class ExpressionBesoinFiles
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
}
