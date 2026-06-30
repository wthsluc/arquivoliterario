<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../entity/livro.php';

class LivroRepository {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexao();
    }

    /** @return Livro[] */
    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM livro WHERE Idusuario = :uid ORDER BY titulo ASC'
        );
        $stmt->execute([':uid' => $usuarioId]);
        $lista = [];
        foreach ($stmt->fetchAll() as $dados) {
            $lista[] = new Livro($dados);
        }
        return $lista;
    }

    public function buscarPorId(int $id): ?Livro {
        $stmt = $this->pdo->prepare('SELECT * FROM livro WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new Livro($dados);
        }

        return null;
    }

    public function salvar(Livro $livro): void {
        if ($livro->getId() > 0) {
            $stmt = $this->pdo->prepare(
                'UPDATE livro  SET titulo = :titulo,
                     descricao = :descricao,
                     situacao = :situacao,
                     nota = :nota,
                     capa = :capa,
                     IdAutor = :autor,
                     IdCategoria = :categoria
                 WHERE id = :id' );
             $stmt->execute([
                ':titulo' => $livro->getTitulo(),
                ':descricao' => $livro->getDescricao(),
                ':situacao' => $livro->getSituacao(),
                ':nota' => $livro->getNota(),
                ':capa' => $livro->getCapa(),
                ':autor' => $livro->getIdAutor(),
                ':categoria' => $livro->getIdCategoria(),
                ':id' => $livro->getId()
            ]);
            return;
        }

        if ($pokemon->getUsuarioId() <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO pokemon (nome, tipo, nivel, usuario_id) VALUES (:nome, :tipo, :nivel, :uid)'
        );
        $stmt->execute([
            ':nome'  => $pokemon->getNome(),
            ':tipo'  => $pokemon->getTipo(),
            ':nivel' => $pokemon->getNivel(),
            ':uid'   => $pokemon->getUsuarioId(),
        ]);

        $pokemon->registrarIdGerado((int) $this->pdo->lastInsertId());
    }

    public function inserir(string $nome, string $tipo, int $nivel, int $usuarioId): void {
        $pokemon = Pokemon::novo($nome, $tipo, $nivel, $usuarioId);
        $this->salvar($pokemon);
    }

    public function atualizar(int $id, string $nome, string $tipo, int $nivel): void {
        $pokemon = $this->buscarPorId($id);

        if ($pokemon === null) {
            throw new RuntimeException('Pokémon não encontrado.');
        }

        $pokemon->alterarDados($nome, $tipo, $nivel);
        $this->salvar($pokemon);
    }

    public function excluir(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM pokemon WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
