<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/Usuario.php';

class UsuarioRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    public function buscarPorEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Usuario($dados);
        }

        return null;
    }

    public function excluir(int $Idusuario): void
{
    $this->pdo->beginTransaction();

    try {

        $stmt = $this->pdo->prepare(
            'DELETE livro_tag
             FROM livro_tag
             INNER JOIN livro
                 ON livro.id = livro_tag.IdLivro
             WHERE livro.Idusuario = :id'
        );

        $stmt->execute([
            ':id' => $IdUsuario
        ]);

        $stmt = $this->pdo->prepare(
            'DELETE FROM livro
             WHERE Idusuario = :id'
        );

        $stmt->execute([
            ':id' => $Idusuario
        ]);

        $stmt = $this->pdo->prepare(
            'DELETE FROM usuario
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $Idusuario
        ]);

        $this->pdo->commit();

    } catch (Exception $e) {

        $this->pdo->rollBack();
        throw $e;

    }
}
}
