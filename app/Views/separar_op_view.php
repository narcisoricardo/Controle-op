<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Separar OP - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <a href="index.php" class="navbar-brand mb-0 h1">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</nav>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-1">Separar OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></h4>
                    <p class="text-muted small mb-3">
                        Obs. do cadastro: <?= htmlspecialchars($op['obs_aguardando'] ?? 'Nenhuma', ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger py-2">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="separar_op.php?id=<?= (int)$op['id'] ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="caixa_tipo" class="form-label">Tipo de Caixa</label>
                                <select class="form-select" id="caixa_tipo" name="caixa_tipo" required autofocus>
                                    <option value="">Selecione...</option>
                                    <option value="transparente">Transparente</option>
                                    <option value="marrom">Marrom</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="caixa_numero" class="form-label">Número da Caixa</label>
                                <input type="number" class="form-control" id="caixa_numero" name="caixa_numero" min="1" placeholder="Ex: 12" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="localizacao" class="form-label">Localização Física</label>
                            <input type="text" class="form-control" id="localizacao" name="localizacao" placeholder="Ex: Prateleira B2 - Corredor 3" required>
                        </div>

                        <div class="mb-3">
                            <label for="anexo_link" class="form-label">Link do Anexo / Laudo (opcional)</label>
                            <input type="url" class="form-control" id="anexo_link" name="anexo_link" placeholder="https://...">
                        </div>

                        <div class="mb-4">
                            <label for="obs_separacao" class="form-label">Observação da Separação (opcional)</label>
                            <textarea class="form-control" id="obs_separacao" name="obs_separacao" rows="2"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-info text-dark fw-semibold">Confirmar Separação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>