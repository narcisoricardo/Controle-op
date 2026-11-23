<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<header class="navbar navbar-dark navbar-industrial px-4 py-2">
    <h1 class="navbar-brand fs-5 mb-0">Controle de OP</h1>
</header>

<main class="container mt-5">
    <section class="row justify-content-center">
        <article class="col-md-5 bg-white p-4 rounded border">
            <h2 class="h4 fw-bold mb-3">Acesso ao Sistema</h2>

            <?php if (!empty($erro)): ?>
                <p class="alert alert-danger py-2">
                    <strong>Erro:</strong> <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <p class="mb-3">
                    <label for="login" class="form-label">Usuário</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Digite seu login" required autofocus>
                </p>

                <p class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                </p>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </article>
    </section>
</main>

<footer class="text-center text-muted small mt-5">
    <p>Sistema de Controle de Ordens de Produção</p>
</footer>

</body>
</html>