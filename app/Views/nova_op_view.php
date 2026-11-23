<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova OP - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<header class="navbar navbar-dark navbar-industrial px-4 py-2">
    <a href="index.php" class="navbar-brand fs-5 mb-0">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</header>

<main class="container mt-4">
    <section class="row justify-content-center">
        <article class="col-md-6 card-sistema p-4">
            <h1 class="h4 fw-bold mb-3">Cadastrar Nova Ordem de Produção</h1>

            <?php if (!empty($erro)): ?>
                <p class="alert alert-danger py-2">
                    <strong>Erro:</strong> <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="nova_op.php">
                <p class="mb-3">
                    <label for="numero_op" class="form-label">Número da OP</label>
                    <input type="text" class="form-control" id="numero_op" name="numero_op" placeholder="Ex: 008" required autofocus>
                </p>

                <p class="mb-4">
                    <label for="obs_aguardando" class="form-label">Observação (opcional)</label>
                    <textarea class="form-control" id="obs_aguardando" name="obs_aguardando" rows="3" placeholder="Ex: Prioridade alta, material urgente..."></textarea>
                </p>

                <p class="text-end mb-0">
                    <a href="index.php" class="btn btn-secondary me-1">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar OP</button>
                </p>
            </form>
        </article>
    </section>
</main>

<footer class="text-center text-muted small mt-5">
    <p>Sistema de Controle de Ordens de Produção</p>
</footer>

</body>
</html>