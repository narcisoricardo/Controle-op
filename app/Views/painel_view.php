<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark navbar-industrial px-4 py-2">
    <span class="navbar-brand mb-0">Controle de OP</span>
    <div class="d-flex align-items-center text-white gap-3">
        <span>
            Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
            <span class="badge bg-light text-dark text-uppercase ms-1">
                <?= htmlspecialchars($_SESSION['usuario_perfil'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success" role="alert">
            Ordem de Produção cadastrada com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['separada'])): ?>
        <div class="alert alert-info" role="alert">
            Separação registrada com sucesso! OP pronta para retirada.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['movimentada'])): ?>
        <div class="alert alert-success" role="alert">
            Movimentação da OP registrada com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['editada'])): ?>
        <div class="alert alert-warning" role="alert">
            Ordem de Produção atualizada com sucesso!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['produzida'])): ?>
        <div class="alert alert-secondary" role="alert">
            Ordem de Produção marcada como <strong>PRODUZIDA</strong> (encerrada).
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['excluida'])): ?>
        <div class="alert alert-danger" role="alert">
            Ordem de Produção excluída permanentemente.
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold">Ordens de Produção</h4>

       <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
            <a href="nova_op.php" class="btn btn-primary">+ Nova OP</a>
        <?php endif; ?>
    </div>

    <!-- Cards de Resumo (KPIs) -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Total</div>
                <div class="kpi-valor text-dark"><?= $resumo['total'] ?></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Aguardando</div>
                <div class="kpi-valor text-warning"><?= $resumo['aguardando'] ?></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Separadas</div>
                <div class="kpi-valor text-primary"><?= $resumo['separada'] ?></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Retiradas</div>
                <div class="kpi-valor text-success"><?= $resumo['retirada'] ?></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Interrompidas</div>
                <div class="kpi-valor text-danger"><?= $resumo['interrompida'] ?></div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card-kpi">
                <div class="kpi-titulo">Produzidas</div>
                <div class="kpi-valor text-secondary"><?= $resumo['produzida'] ?></div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros -->
    <form method="GET" action="index.php" class="card-sistema p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="busca" class="form-label small fw-semibold mb-1">Buscar Nº OP</label>
                <input
                    type="text"
                    class="form-control form-control-sm"
                    id="busca"
                    name="busca"
                    placeholder="Ex: 004"
                    value="<?= htmlspecialchars($filtros['busca'], ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label small fw-semibold mb-1">Status</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Todos os status</option>
                    <option value="aguardando" <?= $filtros['status'] === 'aguardando' ? 'selected' : '' ?>>Aguardando</option>
                    <option value="separada" <?= $filtros['status'] === 'separada' ? 'selected' : '' ?>>Separada</option>
                    <option value="retirada" <?= $filtros['status'] === 'retirada' ? 'selected' : '' ?>>Retirada</option>
                    <option value="interrompida" <?= $filtros['status'] === 'interrompida' ? 'selected' : '' ?>>Interrompida</option>
                    <option value="produzida" <?= $filtros['status'] === 'produzida' ? 'selected' : '' ?>>Produzida</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="periodo" class="form-label small fw-semibold mb-1">Período</label>
                <select class="form-select form-select-sm" id="periodo" name="periodo">
                    <option value="">Todo o período</option>
                    <option value="hoje" <?= $filtros['periodo'] === 'hoje' ? 'selected' : '' ?>>Hoje</option>
                    <option value="semana" <?= $filtros['periodo'] === 'semana' ? 'selected' : '' ?>>Últimos 7 dias (Semana)</option>
                    <option value="mes" <?= $filtros['periodo'] === 'mes' ? 'selected' : '' ?>>Este mês</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Limpar</a>
            </div>
        </div>
    </form>

    <div class="card-sistema">
        <div class="table-responsive">
            <table class="table tabela-ops align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nº OP</th>
                        <th>Status</th>
                        <th>Caixa / Localização</th>
                        <th>Observações</th>
                        <th>Responsáveis</th>
                        <th>Horários</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ops)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Nenhuma Ordem de Produção encontrada para os filtros selecionados.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $podeSeparar  = in_array($_SESSION['usuario_perfil'], ['admin', 'almoxarife'], true);
                        $podeProduzir = in_array($_SESSION['usuario_perfil'], ['admin', 'producao'], true);
                        $ehAdmin      = $_SESSION['usuario_perfil'] === 'admin';
                        ?>
                        <?php foreach ($ops as $op): ?>
                            <tr>
                                <td class="numero-op"><?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="badge-status status-<?= htmlspecialchars($op['status'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($op['status'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($op['caixa_tipo'])): ?>
                                        Caixa <?= htmlspecialchars(ucfirst($op['caixa_tipo']), ENT_QUOTES, 'UTF-8') ?>
                                        <?= $op['caixa_numero'] ? ' nº ' . (int)$op['caixa_numero'] : '' ?>
                                        <?php if (!empty($op['localizacao'])): ?>
                                            <small class="text-muted d-block"><?= htmlspecialchars($op['localizacao'], ENT_QUOTES, 'UTF-8') ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($op['anexo_link'])): ?>
                                            <a href="<?= htmlspecialchars($op['anexo_link'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="small">Ver anexo</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small">
                                    <?php if (!empty($op['obs_aguardando'])): ?>
                                        <div><span class="text-muted">Cad:</span> <?= htmlspecialchars($op['obs_aguardando'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['obs_separacao'])): ?>
                                        <div><span class="text-muted">Sep:</span> <?= htmlspecialchars($op['obs_separacao'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['obs_interrupcao'])): ?>
                                        <div class="text-danger"><span class="text-muted">Int:</span> <?= htmlspecialchars($op['obs_interrupcao'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                    <?php if (empty($op['obs_aguardando']) && empty($op['obs_separacao']) && empty($op['obs_interrupcao'])): ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small">
                                    <div><span class="text-muted">Cad:</span> <?= htmlspecialchars($op['cadastrada_por_nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php if (!empty($op['separada_por_nome'])): ?>
                                        <div><span class="text-muted">Sep:</span> <strong><?= htmlspecialchars($op['separada_por_nome'], ENT_QUOTES, 'UTF-8') ?></strong></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['operador_retirada'])): ?>
                                        <div><span class="text-muted">Ret:</span> <strong><?= htmlspecialchars($op['operador_retirada'], ENT_QUOTES, 'UTF-8') ?></strong></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['operador_interrupcao'])): ?>
                                        <div><span class="text-muted">Int:</span> <strong class="text-danger"><?= htmlspecialchars($op['operador_interrupcao'], ENT_QUOTES, 'UTF-8') ?></strong></div>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-nowrap">
                                    <div><span class="text-muted">Cad:</span> <?= date('d/m H:i', strtotime($op['data_cadastro'])) ?></div>
                                    <?php if (!empty($op['data_separacao'])): ?>
                                        <div><span class="text-muted">Sep:</span> <?= date('d/m H:i', strtotime($op['data_separacao'])) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['data_retirada'])): ?>
                                        <div class="text-success"><span class="text-muted">Ret:</span> <?= date('d/m H:i', strtotime($op['data_retirada'])) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['data_interrupcao'])): ?>
                                        <div class="text-danger"><span class="text-muted">Int:</span> <?= date('d/m H:i', strtotime($op['data_interrupcao'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        <?php if ($op['status'] === 'aguardando' && $podeSeparar): ?>
                                            <a href="separar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                Separar
                                            </a>
                                        <?php elseif (in_array($op['status'], ['separada', 'interrompida'], true) && $podeProduzir): ?>
                                            <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-success">
                                                Retirar
                                            </a>
                                        <?php elseif ($op['status'] === 'retirada' && $podeProduzir): ?>
                                            <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-warning text-dark">
                                                Interromper
                                            </a>
                                            <form method="POST" action="finalizar_op.php" class="d-inline" onsubmit="return confirm('Confirmar que a OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?> foi totalmente produzida?');">
                                                <input type="hidden" name="id" value="<?= (int)$op['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success">Produzida</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($ehAdmin): ?>
                                            <a href="editar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-secondary">
                                                Editar
                                            </a>
                                            <form method="POST" action="excluir_op.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir a OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?>? Esta ação não pode ser desfeita.');">
                                                <input type="hidden" name="id" value="<?= (int)$op['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>