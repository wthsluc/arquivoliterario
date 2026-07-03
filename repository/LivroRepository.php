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
             'SELECT
        livro.*,
        autor.nome AS nomeAutor,
        categoria.nome AS nomeCategoria
     FROM livro
     INNER JOIN autor
         ON livro.IdAutor = autor.id
     INNER JOIN categoria
         ON livro.IdCategoria = categoria.id
     WHERE livro.Idusuario = :uid
     ORDER BY livro.titulo ASC'
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

        if ($livro->getIdusuario() <= 0) {
            throw new InvalidArgumentException('Usuário inválido.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO livro (titulo, descricao, situacao, nota, capa, IdAutor, IdCategoria, Idusuario) VALUES (:titulo, :descricao, :situacao, :nota, :capa, :autor, :categoria, :usuario)'
        );
        $stmt->execute([
            ':titulo' => $livro->getTitulo(),
            ':descricao' => $livro->getDescricao(),
            ':situacao' => $livro->getSituacao(),
            ':nota' => $livro->getNota(),
            ':capa' => $livro->getCapa(),
            ':autor' => $livro->getIdAutor(),
            ':categoria' => $livro->getIdCategoria(),
            ':usuario' => $livro->getIdusuario()
        ]);

        $livro->registrarIdGerado((int) $this->pdo->lastInsertId());
    }

    public function inserir(string $titulo, string $descricao, string $situacao, int $nota, string $capa, int $IdAutor, int $IdCategoria, int $Idusuario): void {
        $livro = Livro::novo($titulo, $descricao, $situacao, $nota, $capa, $IdAutor, $IdCategoria, $Idusuario);
        $this->salvar($livro);
    }

    public function atualizar( int $id, string $titulo, string $descricao, string $situacao, int $nota, string $capa, int $IdAutor, int $IdCategoria): void {
        $livro = $this->buscarPorId($id);

        if ($livro === null) {
            throw new RuntimeException('Livro não encontrado.');
        }

        $livro->alterarDados( $titulo, $descricao, $situacao, $nota, $capa, $IdAutor, $IdCategoria);
        $this->salvar($livro);
    }

    public function excluir(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM livro WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public function buscarTagsDoLivro(int $IdLivro): array {

    $stmt = $this->pdo->prepare(
        'SELECT IdTag
         FROM livro_tag
         WHERE IdLivro = :livro'
    );

    $stmt->execute([
        ':livro' => $IdLivro
    ]);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function salvarTags(int $IdLivro, array $tags): void {

    
    $stmt = $this->pdo->prepare(
        'DELETE FROM livro_tag WHERE IdLivro = :livro'
    );

    $stmt->execute([
        ':livro' => $IdLivro
    ]);

    
    $stmt = $this->pdo->prepare(
        'INSERT INTO livro_tag (IdLivro, IdTag)
         VALUES (:livro, :tag)'
    ); 

    foreach ($tags as $IdTag) {

        $stmt->execute([
            ':livro' => $IdLivro,
            ':tag' => $IdTag
        ]);

    }
    }
    public function buscarNomesTags(int $IdLivro): string {

    $stmt = $this->pdo->prepare(
        'SELECT tag.nome
         FROM tag
         INNER JOIN livro_tag
             ON tag.id = livro_tag.IdTag
         WHERE livro_tag.IdLivro = :livro
         ORDER BY tag.nome'
    );

    $stmt->execute([
        ':livro' => $IdLivro
    ]);

    $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return implode(', ', $tags);
    }
    public function buscarNomeAutor(int $idAutor): string
    {
    $stmt = $this->pdo->prepare(
        'SELECT nome FROM autor WHERE id = :id'
    );

    $stmt->execute([
        ':id' => $idAutor
    ]);

    return $stmt->fetchColumn() ?: '';
    }

    public function buscarNomeCategoria(int $idCategoria): string
    {
    $stmt = $this->pdo->prepare(
        'SELECT nome FROM categoria WHERE id = :id'
    );

    $stmt->execute([
        ':id' => $idCategoria
    ]);

    return $stmt->fetchColumn() ?: '';
    }
    public function listarCategoria(int $usuarioId, int $categoriaId): array
{
    $stmt = $this->pdo->prepare(
        'SELECT *
         FROM livro
         WHERE Idusuario = :uid
         AND IdCategoria = :categoria
         ORDER BY titulo ASC'
    );

    $stmt->execute([
        ':uid' => $usuarioId,
        ':categoria' => $categoriaId
    ]);

    $lista = [];

    foreach ($stmt->fetchAll() as $dados) {
        $lista[] = new Livro($dados);
    }

    return $lista;
}
}

