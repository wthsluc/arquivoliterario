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
  $IdAutor = (int) ($_POST['IdAutor'] ?? 0);
  $IdCategoria = (int) ($_POST['IdCategoria'] ?? 0);
  
  $tagsSelecionadas = $_POST['tags'] ?? [];

    try {
        $livro = Livro::novo($titulo, $descricao, $situacao, $nota, $capa, $IdAutor, $IdCategoria, $_SESSION['usuario_id']);

       $repo->salvar($livro);

        header('Location: index.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Novo livro</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="livro_create.php">

    <div class="form-group">
      <label for="nome">Nome do Livro</label>
      <input
        type="text"
        id="nome"
        name="nome"
        placeholder="Ex: A paixão segundo G.H"
        value="<?= htmlspecialchars($nome) ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="tipo">Tipo</label>
      <select id="tipo" name="tipo" required>
        <option value="">Selecione o tipo...</option>
        <?php foreach ($tipos as $t): ?>
          <?php
            $selecionado = '';
            if ($tipo === $t) {
                $selecionado = 'selected';
            }
          ?>
          <option value="<?= $t ?>" <?= $selecionado ?>>
            <?= $t ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="nivel">Nível (1 – 100)</label>
      <input
        type="number"
        id="nivel"
        name="nivel"
        min="1"
        max="100"
        value="<?= $nivel ?>"
        required
      />
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Cadastrar Pokémon</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>

  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
