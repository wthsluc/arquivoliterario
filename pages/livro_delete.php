<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/LivroRepository.php';

$repo = new LivroRepository();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repo->excluir($livro->getId());
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Excluir livro</h2>
  <a href="index.php" class="btn btn-ghost">← Voltar</a>
</div>

<div class="confirm-card">
  <h3>Você tem certeza?</h3>
  <p>
    Você está prestes a excluir o livro
    <strong><?= htmlspecialchars($livro->getTitulo()) ?></strong>
    
    Esta ação não pode ser desfeita.
  </p>

  <form method="POST" action="livro_delete.php?id=<?= $livro->getId() ?>">
    <div class="form-actions">
      <button type="submit" class="btn btn-excluir">Sim, excluir</button>
      <a href="index.php" class="btn btn-ghost">Cancelar</a>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
