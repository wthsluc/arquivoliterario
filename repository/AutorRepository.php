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

    public function BuscarOuCriar(string $nome): int
    {
    $sql = "SELECT id FROM autor WHERE nome = :nome";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);

    $autor = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($autor) {
        return (int)$autor['id'];
    }

    $sql = "INSERT INTO autor (nome) VALUES (:nome)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);

    return (int)$this->pdo->lastInsertId();
}
}
