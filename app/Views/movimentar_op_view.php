<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentar OP - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<header class="navbar navbar-dark navbar-industrial px-4 py-2">
    <a href="index.php" class="navbar-brand fs-5 mb-0">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</header>

<main class="container mt-4 mb-5">
    <section class="row justify-content-center">
        <article class="col-md-6">
            <section class="card-sistema p-4 mb-4">
                <h1 class="h4 fw-bold mb-1">Movimentar OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-muted small mb-3">
                    Localização atual:
                    <strong>
                        Caixa <?= htmlspecialchars(ucfirst($op['caixa_tipo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        nº <?= (int)($op['caixa_numero'] ?? 0) ?>
                        (<?= htmlspecialchars($op['localizacao'] ?? '', ENT_QUOTES, 'UTF-8') ?>)
                    </strong>
                </p>

                <?php if (!empty($erro)): ?>
                    <p class="alert alert-danger py-2">
                        <strong>Erro:</strong> <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="movimentar_op.php?id=<?= (int)$op['id'] ?>">
                    <p class="mb-3">
                        <label for="operador_nome" class="form-label">Nome do Operador</label>
                        <input type="text" class="form-control" id="operador_nome" name="operador_nome" list="lista_operadores" placeholder="Quem está retirando ou interrompendo..." required autofocus>
                        <datalist id="lista_operadores">
                            <?php foreach ($operadores as $nomeOp): ?>
                                <option value="<?= htmlspecialchars($nomeOp, ENT_QUOTES, 'UTF-8') ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </p>

                    <p class="mb-3">
                        <label for="tipo" class="form-label">Ação</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <?php if (in_array($op['status'], ['separada', 'interrompida'], true)): ?>
                                <option value="retirada">Retirar para Produção</option>
                            <?php endif; ?>
                            <?php if ($op['status'] === 'retirada'): ?>
                                <option value="interrupcao">Interromper Produção</option>
                            <?php endif; ?>
                        </select>
                    </p>

                    <p class="mb-3">
                        <label for="turno" class="form-label">Turno</label>
                        <select class="form-select" id="turno" name="turno" required>
                            <option value="">Selecione...</option>
                            <option value="primeiro">1º Turno</option>
                            <option value="segundo">2º Turno</option>
                            <option value="terceiro">3º Turno</option>
                        </select>
                    </p>

                    <p class="mb-3">
                        <label for="maquina_id" class="form-label">Injetora</label>
                        <select class="form-select" id="maquina_id" name="maquina_id">
                            <option value="">Selecione a injetora...</option>
                            <?php foreach ($maquinas as $m): ?>
                                <option value="<?= (int)$m['id'] ?>">
                                    <?= htmlspecialchars($m['nome'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <p class="mb-4">
                        <label for="observacao" class="form-label">Observação / Motivo</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="2" placeholder="Obrigatório em caso de interrupção..."></textarea>
                    </p>

                    <p class="text-end mb-0">
                        <a href="index.php" class="btn btn-secondary me-1">Cancelar</a>
                        <button type="submit" class="btn btn-success">Registrar Movimentação</button>
                    </p>
                </form>
            </section>

            <?php if (!empty($historico)): ?>
                <section class="card-sistema p-4">
                    <h2 class="h6 fw-bold mb-3">Histórico desta OP</h2>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($historico as $h): ?>
                            <li class="list-group-item small px-0">
                                <strong class="text-uppercase">
                                    <?= htmlspecialchars($h['tipo'], ENT_QUOTES, 'UTF-8') ?>
                                    (<?= htmlspecialchars($h['turno'], ENT_QUOTES, 'UTF-8') ?> turno)
                                </strong>
                                — <span class="text-muted"><?= date('d/m/Y H:i', strtotime($h['data_hora'])) ?></span>

                                <?php if (!empty($h['operador_nome'])): ?>
                                    <br>Operador: <strong><?= htmlspecialchars($h['operador_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php endif; ?>
                                <?php if (!empty($h['maquina_nome'])): ?>
                                    <br>Máquina: <?= htmlspecialchars($h['maquina_nome'], ENT_QUOTES, 'UTF-8') ?>
                                <?php endif; ?>
                                <?php if (!empty($h['observacao'])): ?>
                                    <br><span class="text-muted">Obs: <?= htmlspecialchars($h['observacao'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>
        </article>
    </section>
</main>

<footer class="text-center text-muted small mb-4">
    <p>Sistema de Controle de Ordens de Produção</p>
</footer>

</body>
</html>