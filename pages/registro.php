<?php

require_once __DIR__ . '/../config/database.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } else {

        $pdo = getConexao();

        $stmt = $pdo->prepare(
            'SELECT id FROM usuario WHERE email = :email'
        );

        $stmt->execute([
            ':email' => $email
        ]);

        if ($stmt->fetch()) {

            $erro = 'Este e-mail já está cadastrado.';

        } else {

            $senhaHash = hash('sha256', $senha);

            $stmt = $pdo->prepare(
                'INSERT INTO usuario (nome, email, senha)
                 VALUES (:nome, :email, :senha)'
            );

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senhaHash
            ]);

            header('Location: login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Arquivo Literário</title>
  <link rel="stylesheet" href="../assets/style.css" />
</head>
<body class="login-body">

<div class="login-card>

<h2>Cadastrar Usuário</h2>

<?php if ($erro !== ''): ?>

```
<p><?= htmlspecialchars($erro) ?></p>
```

<?php endif; ?>

<form method="POST">

```
<label>Nome</label>
<input type="text" name="nome" required>

<br><br>

<label>Email</label>
<input type="email" name="email" required>

<br><br>

<label>Senha</label>
<input type="password" name="senha" required>

<br><br>

<button type="submit">
    Cadastrar
</button>
```

</form>

<p>
    Já possui conta?
    <a href="login.php">Entrar</a>
</p>
</div>