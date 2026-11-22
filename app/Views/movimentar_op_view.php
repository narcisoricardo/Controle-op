<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentar OP - Controle de OP</title>
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
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-1">Movimentar OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></h4>
                    <p class="text-muted small mb-3">
                        Localização atual:
                        <strong>
                            Caixa <?= htmlspecialchars(ucfirst($op['caixa_tipo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            nº <?= (int)($op['caixa_numero'] ?? 0) ?>
                            (<?= htmlspecialchars($op['localizacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>)
                        </strong>
                    </p>

                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger py-2">
                            <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="movimentar_op.php?id=<?= (int)$op['id'] ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">Ação</label>
                                <select class="form-select" id="tipo" name="tipo" required autofocus>
                                    <?php if (in_array($op['status'], ['separada', 'interrompida'], true)): ?>
                                        <option value="retirada">Retirar para Produção</option>
                                    <?php endif; ?>
                                    <?php if ($op['status'] === 'retirada'): ?>
                                        <option value="interrupcao">Interromper Produção</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="turno" class="form-label">Turno</label>
                                <select class="form-select" id="turno" name="turno" required>
                                    <option value="">Selecione...</option>
                                    <option value="primeiro">1º Turno</option>
                                    <option value="segundo">2º Turno</option>
                                    <option value="terceiro">3º Turno</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="maquina_id" class="form-label">Injetora</label>
                            <select class="form-select" id="maquina_id" name="maquina_id">
                                <option value="">Selecione a injetora...</option>
                                <?php foreach ($maquinas as $m): ?>
                                    <option value="<?= (int)$m['id'] ?>">
                                        <?= htmlspecialchars($m['nome'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="observacao" class="form-label">Observação / Motivo</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="2" placeholder="Obrigatório em caso de interrupção..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Registrar Movimentação</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($historico)): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">Histórico desta OP</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($historico as $h): ?>
                            <li class="list-group-item small">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-uppercase">
                                        <?= htmlspecialchars($h['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                        (<?= htmlspecialchars($h['turno'], ENT_QUOTES, 'UTF-8') ?> turno)
                                    </strong>
                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($h['data_hora'])) ?></span>
                                </div>
                                <?php if (!empty($h['maquina_nome'])): ?>
                                    <div>Máquina: <?= htmlspecialchars($h['maquina_nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>
                                <?php if (!empty($h['observacao'])): ?>
                                    <div class="text-muted">Obs: <?= htmlspecialchars($h['observacao'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>