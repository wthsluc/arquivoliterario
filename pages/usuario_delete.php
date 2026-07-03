
<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../repository/UsuarioRepository.php';
$usuarioRepo = new UsuarioRepository();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuarioRepo->excluir($_SESSION['Idusuario']);

    session_unset();
    session_destroy();

    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../includes/header.php';

?>



<div class="form-card">

    <h2>Excluir Conta</h2>

    <p>
        Tem certeza que deseja excluir sua conta?
    </p>

    <p>
        <strong>Todos os seus livros e informações serão apagados permanentemente.</strong>
    </p>

    <div class="form-actions">

        <form method="POST">

    <button
        type="submit"
        class="btn btn-danger"
        onclick="return confirm('Tem certeza que deseja excluir sua conta?');">

        Sim, excluir minha conta

    </button>

       </form>

        <a href="index.php" class="btn btn-ghost">
            Cancelar
        </a>

    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

