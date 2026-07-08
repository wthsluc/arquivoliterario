<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arquivo Literário</title>

    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<header class="site-header">

    <div class="header-inner">

        <a href="../pages/index.php" class="logo">
             Arquivo Literário
        </a>

        <?php
            $nomeUser = $_SESSION['usuario_nome'] ?? 'Usuário';
        ?>

        <details class="perfil">

    <summary class="foto-perfil">

        <?= strtoupper(substr($nomeUser,0,1)) ?>

    </summary>

    <div class="menu-perfil">

        <div class="menu-nome">
            <?= htmlspecialchars($nomeUser) ?>
        </div>

        <a href="../pages/index.php?perfil=1">
            Informações
        </a>

        <a href="../pages/logout.php">
            Sair
        </a>

    </div>
        </details>
</header>

<main class="container">