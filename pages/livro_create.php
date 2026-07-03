<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/LivroRepository.php';
require_once __DIR__ . '/../repository/AutorRepository.php';
require_once __DIR__ . '/../repository/CategoriaRepository.php';
require_once __DIR__ . '/../repository/TagRepository.php';

$repo = new LivroRepository();
$autorRepo = new AutorRepository();
$categoriaRepo = new CategoriaRepository();
$tagRepo = new TagRepository();

$autores = $autorRepo->listarTodos();
$categorias = $categoriaRepo->listarTodos();
$tags = $tagRepo->listarTodos();

$titulo = '';
$descricao = '';
$situacao = '';
$nota = 0;
$capa = '';
$IdAutor = 0;
$IdCategoria = 0;
$erro = '';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = trim($_POST['titulo'] ?? '');
  $descricao = trim($_POST['descricao'] ?? '');
  $situacao = trim($_POST['situacao'] ?? '');
  $nota = (int) ($_POST['nota'] ?? 0);
  $capa = trim($_POST['capa'] ?? '');
  $nomeAutor = trim($_POST['autor'] ?? '');
  $IdCategoria = (int) ($_POST['IdCategoria'] ?? 0);
  $tagsSelecionadas = $_POST['tags'] ?? [];

    try {

       $IdAutor = $autorRepo->buscarOuCriar($nomeAutor);
        $livro = Livro::novo($titulo, $descricao, $situacao, $nota, $capa, $IdAutor, $IdCategoria, $_SESSION['Idusuario']);

       $repo->salvar($livro);
       $repo->salvarTags(
       $livro->getId(),
       $tagsSelecionadas
       );

        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <h2>Novo Livro</h2>

    <a href="index.php" class="btn btn-ghost">
        ← Voltar
    </a>
</div>

<?php if ($erro !== ''): ?>
    <div class="alert alert-erro">
        <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<div class="form-card">

<form method="POST" action="livro_create.php">

    <div class="form-group">
        <label for="titulo">Título</label>

        <input
            type="text"
            id="titulo"
            name="titulo"
            value="<?= htmlspecialchars($titulo) ?>"
            required>
    </div>

    <div class="form-group">
        <label for="descricao">Descrição</label>

        <textarea
            id="descricao"
            name="descricao"><?= htmlspecialchars($descricao) ?></textarea>
    </div>

    <div class="form-group">
        <label for="situacao">Situação</label>

        <select
            id="situacao"
            name="situacao"
            required>

            <option value="QUERO_LER">Quero Ler</option>
            <option value="LENDO">Lendo</option>
            <option value="LIDO">Lido</option>

        </select>
    </div>

    <div class="form-group">
        <label for="nota">Nota</label>

        <input
            type="number"
            id="nota"
            name="nota"
            min="0"
            max="5"
            value="<?= $nota ?>">
    </div>

    <div class="form-group">
        <label for="capa">Capa</label>

        <input
            type="text"
            id="capa"
            name="capa"
            value="<?= htmlspecialchars($capa) ?>">
    </div>

    <div class="form-group">
    <label for="autor">Autor</label>

    <input
        type="text"
        id="autor"
        name="autor"
        value=""
        required>
</div>

    <div class="form-group">
        <label for="IdCategoria">Categoria</label>

        <select
            id="IdCategoria"
            name="IdCategoria"
            required>

            <option value="">Selecione...</option>

            <?php foreach ($categorias as $categoria): ?>

                <option value="<?= $categoria['id'] ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <div class="form-group">
        <label>Tags</label>

        <?php foreach ($tags as $tag): ?>

            <label>

                <input
                    type="checkbox"
                    name="tags[]"
                    value="<?= $tag['id'] ?>">

                <?= htmlspecialchars($tag['nome']) ?>

            </label>

            <br>

        <?php endforeach; ?>

    </div>

    <div class="form-actions">

        <button
            type="submit"
            class="btn btn-primary">

            Cadastrar Livro

        </button>

        <a
            href="index.php"
            class="btn btn-ghost">

            Cancelar

        </a>

    </div>

</form>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>