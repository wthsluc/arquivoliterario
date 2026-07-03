<?php

require_once __DIR__ . '/../config/database.php';

class CategoriaRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function listarTodos(): array {
        $sql = "SELECT * FROM categoria ORDER BY nome";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}