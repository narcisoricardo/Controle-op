<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar OP - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark navbar-industrial px-4 py-2">
    <a href="index.php" class="navbar-brand mb-0">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card-sistema">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Editar OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></h4>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger py-2">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="editar_op.php?id=<?= (int)$op['id'] ?>">
                        <div class="mb-3">
                            <label for="numero_op" class="form-label">Número da OP</label>
                            <input
                                type="text"
                                class="form-control"
                                id="numero_op"
                                name="numero_op"
                                value="<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-4">
                            <label for="obs_aguardando" class="form-label">Observação do Cadastro</label>
                            <textarea
                                class="form-control"
                                id="obs_aguardando"
                                name="obs_aguardando"
                                rows="3"
                                placeholder="Deixe em branco para remover a observação..."
                            ><?= htmlspecialchars($op['obs_aguardando'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>