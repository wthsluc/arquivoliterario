<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/LivroRepository.php';

$repo = new LivroRepository();

$erro = '';
$titulo = '';
$descricao = '';
$situacao = '';
$nota = 0;
$capa = '';
$situacao = '';
$IdAutor = 1;
$IdCategoria = '';
$tags = '';



$erro = '';
$nome = '';
$tipo = '';
$nivel = 1;

$tipos = ['Fogo', 'Água', 'Planta', 'Elétrico', 'Terra', 'Voador',
          'Psíquico', 'Gelo', 'Lutador', 'Venenoso', 'Normal', 'Fantasma',
          'Dragão', 'Pedra', 'Aço', 'Fada'];

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
