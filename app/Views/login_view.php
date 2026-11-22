<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body class="tela-login">

<div class="card-sistema card-login p-4">
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-1">Controle de OP</h4>
        <p class="text-muted small mb-0">Injeção Plástica • Almoxarifado e Produção</p>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger py-2">
            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="login" class="form-label">Usuário</label>
            <input type="text" class="form-control" id="login" name="login" required autofocus>
        </div>

        <div class="mb-4">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>

</body>
</html>