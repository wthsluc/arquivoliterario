<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/LivroRepository.php';

$repo     = new LivroRepository();
$livros = $repo->listarPorUsuario($_SESSION['Idusuario']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
  <h2>Meus Livros</h2>
  <a href="livro_create.php" class="btn btn-primary">+ Novo livro</a>
</div>

<?php if (empty($livros)): ?>
  <div class="empty-state">
    <p>Você ainda não cadastrou nenhum livro.</p>
    <a href="livro_create.php" class="btn btn-primary">Cadastrar agora</a>
  </div>
<?php else: ?>
  <div class="table-wrapper">
    <table class="data-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Descrição</th>
          <th>Situação</th>
          <th>Nota</th>
          <th>Capa</th>
          <th>Autor</th>
          <th>Categoria</th>
          <th>tags</th>
          <th>ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($livros as $livro): ?>

         
        <tr>
            <td><?= $livro->getId() ?></td>
            <td><strong><?= htmlspecialchars($livro->getTitulo()) ?></strong></td>
            <td><?= htmlspecialchars($livro->getDescricao()) ?></td>
            <td><?= htmlspecialchars($livro->getSituacao()) ?></td>
            <td><?= $livro->getNota() ?></td>
            <td><?= htmlspecialchars($livro->getCapa()) ?></td>
            <td><?= htmlspecialchars($repo->buscarNomeAutor($livro->getIdAutor())) ?></td>
            <td><?= htmlspecialchars($repo->buscarNomeCategoria($livro->getIdCategoria())) ?></td>
            <td><?= htmlspecialchars($repo->buscarNomesTags($livro->getId())) ?></td>
            <td class="acoes">
              <a href="livro_edit.php?id=<?= $livro->getId() ?>" class="btn btn-sm btn-editar">Editar</a>
              <a href="livro_delete.php?id=<?= $livro->getId() ?>" class="btn btn-sm btn-excluir">Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
