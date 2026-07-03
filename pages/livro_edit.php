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
$id = 0;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}

$livro = null;
if ($id > 0) {
    $livro = $repo->buscarPorId($id);
}

if ($livro === null || $livro->getIdusuario() !== $_SESSION['Idusuario']) {
    header('Location: index.php');
    exit;
}

$autores = $autorRepo->listarTodos();
$categorias = $categoriaRepo->listarTodos();
$tags = $tagRepo->listarTodos();
$tagsDoLivro = $repo->buscarTagsDoLivro($livro->getId());

$erro = '';
$titulo = $livro->getTitulo();
$descricao = $livro->getDescricao();
$situacao = $livro->getSituacao();
$nota = $livro->getNota();
$capa = $livro->getCapa();
$IdAutor = $livro->getIdAutor();
$IdCategoria = $livro->getIdCategoria();




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $situacao = trim($_POST['situacao'] ?? '');
    $nota = (int) ($_POST['nota'] ?? 0);
    $nomeAutor = trim($_POST['autor'] ?? '');
    $IdAutor = $autorRepo->buscarOuCriar($nomeAutor);
    $IdCategoria = (int) ($_POST['IdCategoria'] ?? 0);

    $tagsSelecionadas = $_POST['tags'] ?? [];
    

    $capa = $livro->getCapa(); 

   if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {

    $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

    $extensao = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, $permitidos)) {
        throw new InvalidArgumentException('Formato de imagem inválido.');
    }

    if ($_FILES['capa']['size'] > 2 * 1024 * 1024) {
        throw new InvalidArgumentException('A imagem deve ter no máximo 2MB.');
    }

    $novoNome = uniqid() . '.' . $extensao;

    move_uploaded_file(
        $_FILES['capa']['tmp_name'],
        __DIR__ . '/../uploads/' . $novoNome
    );

    $capa = $novoNome;
}
    try {
       $livro->alterarDados($titulo,$descricao,$situacao,$nota,$capa,$IdAutor,$IdCategoria);
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
    <h2>Editar Livro</h2>

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

<form 
    method="POST"
    action="livro_edit.php?id=<?= $livro->getId() ?>"
    enctype="multipart/form-data">

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

            <option value="QUERO_LER" <?= $situacao === 'QUERO_LER' ? 'selected' : '' ?>>
                Quero Ler
            </option>

            <option value="LENDO" <?= $situacao === 'LENDO' ? 'selected' : '' ?>>
                Lendo
            </option>

            <option value="LIDO" <?= $situacao === 'LIDO' ? 'selected' : '' ?>>
                Lido
            </option>

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
           
          type="file"
          id="capa"
          name="capa"
          accept=".jpg,.jpeg,.png,.webp">
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

                <option
                    value="<?= $categoria['id'] ?>"
                    <?= $categoria['id'] == $IdCategoria ? 'selected' : '' ?>>

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
               value="<?= $tag['id'] ?>"
               <?= in_array($tag['id'], $tagsDoLivro) ? 'checked' : '' ?>>

<?= htmlspecialchars($tag['nome']) ?>

            </label>

            <br>

        <?php endforeach; ?>

    </div>

    <div class="form-actions">

        <button
            type="submit"
            class="btn btn-primary">

            Salvar Alterações

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
