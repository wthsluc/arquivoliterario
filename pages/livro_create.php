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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $tipo  = trim($_POST['tipo'] ?? '');
    $nivel = (int) ($_POST['nivel'] ?? 1);

    try {
        $pokemon = Pokemon::novo($nome, $tipo, $nivel, $_SESSION['Idusuario']);
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
  <h2>Novo Pokémon</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<?php if ($erro !== ''): ?>
  <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="pokemon_create.php">

    <div class="form-group">
      <label for="nome">Nome do Pokémon</label>
      <input
        type="text"
        id="nome"
        name="nome"
        placeholder="Ex: Charmander"
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
