<?php

require_once __DIR__ . '/../config/database.php';

class AutorRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function listarTodos(): array {
        $sql = "SELECT * FROM autor ORDER BY nome";
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}