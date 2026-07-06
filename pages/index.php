<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/LivroRepository.php';
require_once __DIR__ . '/../repository/CategoriaRepository.php';

$repo     = new LivroRepository();
$idLivro = (int) ($_GET['id'] ?? 0);

$livroSelecionado = null;

if ($idLivro > 0) {
    $livroSelecionado = $repo->buscarPorId($idLivro);
}
$categoriaSelecionada = (int) ($_GET['categoria'] ?? 0);

if ($categoriaSelecionada > 0) {
    $livros = $repo->listarCategoria(
        $_SESSION['Idusuario'],
        $categoriaSelecionada
    );
} else {
    $livros = $repo->listarPorUsuario($_SESSION['Idusuario']);
}
$categoriaRepo = new CategoriaRepository();
$categorias = $categoriaRepo->listarTodos();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meus Livros</h2>
  <a href="livro_create.php" class="btn btn-primary">+ Novo livro</a>
</div>
<form method="GET" class="form-inline" style="margin-bottom:20px;">

    <label for="categoria">Categoria:</label>

    <select id="categoria" name="categoria">

        <option value="0">Todas</option>

        <?php foreach ($categorias as $categoria): ?>

            <option
                value="<?= $categoria['id'] ?>"
                <?= $categoriaSelecionada == $categoria['id'] ? 'selected' : '' ?>>

                <?= htmlspecialchars($categoria['nome']) ?>

            </option>

        <?php endforeach; ?>

    </select>

    <button type="submit" class="btn btn-primary">
        Filtrar
    </button>

</form>

<div class="lista-livros">
.
</div>
<?php if (empty($livros)): ?>
  <div class="empty-state">
    <p>Você ainda não cadastrou nenhum livro.</p>
    <a href="livro_create.php" class="btn btn-primary">Cadastrar agora</a>
  </div>

  <?php else: ?>

<div class="lista-livros">

    <?php foreach ($livros as $livro): ?>

        <div class="livro-item">

            <span class="titulo-livro">
                <?= htmlspecialchars($livro->getTitulo()) ?>
            </span>

            <a
                class="btn-vermais"
                href="index.php?id=<?= $livro->getId() ?>">
                Ver mais
            </a>

        </div>

    <?php endforeach; ?>

</div>

<?php endif; ?>

<?php if ($livroSelecionado): ?>

<div class="modal-overlay">

    <div class="modal-livro">

        <a href="index.php" class="fechar">✕</a>

        <h2><?= htmlspecialchars($livroSelecionado->getTitulo()) ?></h2>

        <?php if ($livroSelecionado->getCapa() !== ''): ?>
            <img
                src="../uploads/<?= htmlspecialchars($livroSelecionado->getCapa()) ?>"
                class="modal-capa">
        <?php endif; ?>

        <div class="info-livro">

    <div class="campo">
        <h4>🖋 Autor</h4>
        <span><?= htmlspecialchars($repo->buscarNomeAutor($livroSelecionado->getIdAutor())) ?></span>
    </div>

    <div class="campo">
        <h4>📚 Categoria</h4>
        <span><?= htmlspecialchars($repo->buscarNomeCategoria($livroSelecionado->getIdCategoria())) ?></span>
    </div>

    <div class="campo">
        <h4>📖 Situação</h4>
        <span><?= htmlspecialchars($livroSelecionado->getSituacao()) ?></span>
    </div>

    <div class="campo">
        <h4>⭐ Nota</h4>
        <span><?= str_repeat('★', $livroSelecionado->getNota()) ?></span>
    </div>

    <div class="campo">
        <h4>🏷 Tags</h4>
        <span><?= htmlspecialchars($repo->buscarNomesTags($livroSelecionado->getId())) ?></span>
    </div>

</div>

<hr>


        <p><?= nl2br(htmlspecialchars($livroSelecionado->getDescricao())) ?></p>

        <div class="modal-botoes">

            <a
                href="livro_edit.php?id=<?= $livroSelecionado->getId() ?>"
                class="btn btn-primary">

                Editar

            </a>

            <a
                href="livro_delete.php?id=<?= $livroSelecionado->getId() ?>"
                class="btn btn-danger">

                Excluir

            </a>

        </div>

    </div>

</div>

<?php endif; ?>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>
