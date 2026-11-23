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

<header class="navbar navbar-dark navbar-industrial px-4 py-2">
    <h1 class="navbar-brand fs-5 mb-0">Controle de OP</h1>
    <p class="text-white mb-0">
        Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span class="badge bg-light text-dark text-uppercase ms-1">
            <?= htmlspecialchars($_SESSION['usuario_perfil'], ENT_QUOTES, 'UTF-8') ?>
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm ms-3">Sair</a>
    </p>
</header>

<main class="container mt-4 mb-5">
    <?php if (isset($_GET['sucesso'])): ?>
        <p class="alert alert-success">Ordem de Produção cadastrada com sucesso!</p>
    <?php endif; ?>
    <?php if (isset($_GET['separada'])): ?>
        <p class="alert alert-info">Separação registrada com sucesso! OP pronta para retirada.</p>
    <?php endif; ?>
    <?php if (isset($_GET['movimentada'])): ?>
        <p class="alert alert-success">Movimentação da OP registrada com sucesso!</p>
    <?php endif; ?>
    <?php if (isset($_GET['editada'])): ?>
        <p class="alert alert-warning">Ordem de Produção atualizada com sucesso!</p>
    <?php endif; ?>
    <?php if (isset($_GET['produzida'])): ?>
        <p class="alert alert-secondary">Ordem de Produção marcada como <strong>PRODUZIDA</strong> (encerrada).</p>
    <?php endif; ?>
    <?php if (isset($_GET['excluida'])): ?>
        <p class="alert alert-danger">Ordem de Produção excluída permanentemente.</p>
    <?php endif; ?>

    <!-- Cabeçalho da Seção e Botões do Admin -->
    <section class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 fw-bold mb-0">Ordens de Produção</h2>

        <?php if ($_SESSION['usuario_perfil'] === 'admin'): ?>
            <p class="mb-0">
                <a href="usuarios.php" class="btn btn-outline-primary me-1">Usuários</a>
                <a href="nova_op.php" class="btn btn-primary">+ Nova OP</a>
            </p>
        <?php endif; ?>
    </section>

    <!-- Resumo (KPIs) usando Lista Semântica -->
    <section class="mb-3">
        <ul class="row g-2 list-unstyled mb-0">
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Total</span>
                    <strong class="kpi-valor text-dark"><?= $resumo['total'] ?></strong>
                </article>
            </li>
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Aguardando</span>
                    <strong class="kpi-valor text-warning"><?= $resumo['aguardando'] ?></strong>
                </article>
            </li>
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Separadas</span>
                    <strong class="kpi-valor text-primary"><?= $resumo['separada'] ?></strong>
                </article>
            </li>
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Retiradas</span>
                    <strong class="kpi-valor text-success"><?= $resumo['retirada'] ?></strong>
                </article>
            </li>
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Interrompidas</span>
                    <strong class="kpi-valor text-danger"><?= $resumo['interrompida'] ?></strong>
                </article>
            </li>
            <li class="col-6 col-md-2">
                <article class="card-kpi">
                    <span class="kpi-titulo d-block">Produzidas</span>
                    <strong class="kpi-valor text-secondary"><?= $resumo['produzida'] ?></strong>
                </article>
            </li>
        </ul>
    </section>

    <!-- Barra de Filtros -->
    <section class="card-sistema p-3 mb-3">
        <form method="GET" action="index.php" class="row g-2 align-items-end">
            <p class="col-md-3 mb-0">
                <label for="busca" class="form-label small fw-semibold mb-1">Buscar Nº OP</label>
                <input type="text" class="form-control form-control-sm" id="busca" name="busca" placeholder="Ex: 004" value="<?= htmlspecialchars($filtros['busca'], ENT_QUOTES, 'UTF-8') ?>">
            </p>

            <p class="col-md-3 mb-0">
                <label for="status" class="form-label small fw-semibold mb-1">Status</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Todos os status</option>
                    <option value="aguardando" <?= $filtros['status'] === 'aguardando' ? 'selected' : '' ?>>Aguardando</option>
                    <option value="separada" <?= $filtros['status'] === 'separada' ? 'selected' : '' ?>>Separada</option>
                    <option value="retirada" <?= $filtros['status'] === 'retirada' ? 'selected' : '' ?>>Retirada</option>
                    <option value="interrompida" <?= $filtros['status'] === 'interrompida' ? 'selected' : '' ?>>Interrompida</option>
                    <option value="produzida" <?= $filtros['status'] === 'produzida' ? 'selected' : '' ?>>Produzida</option>
                </select>
            </p>

            <p class="col-md-3 mb-0">
                <label for="periodo" class="form-label small fw-semibold mb-1">Período</label>
                <select class="form-select form-select-sm" id="periodo" name="periodo">
                    <option value="">Todo o período</option>
                    <option value="hoje" <?= $filtros['periodo'] === 'hoje' ? 'selected' : '' ?>>Hoje</option>
                    <option value="semana" <?= $filtros['periodo'] === 'semana' ? 'selected' : '' ?>>Últimos 7 dias (Semana)</option>
                    <option value="mes" <?= $filtros['periodo'] === 'mes' ? 'selected' : '' ?>>Este mês</option>
                </select>
            </p>

            <p class="col-md-3 d-flex gap-2 mb-0">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Limpar</a>
            </p>
        </form>
    </section>

    <!-- Tabela de OPs -->
    <section class="card-sistema table-responsive">
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
                                        <br><small class="text-muted"><?= htmlspecialchars($op['localizacao'], ENT_QUOTES, 'UTF-8') ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($op['anexo_link'])): ?>
                                        <br><a href="<?= htmlspecialchars($op['anexo_link'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="small">Ver anexo</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?php if (!empty($op['obs_aguardando'])): ?>
                                    <span class="text-muted">Cad:</span> <?= htmlspecialchars($op['obs_aguardando'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?php endif; ?>
                                <?php if (!empty($op['obs_separacao'])): ?>
                                    <span class="text-muted">Sep:</span> <?= htmlspecialchars($op['obs_separacao'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?php endif; ?>
                                <?php if (!empty($op['obs_interrupcao'])): ?>
                                    <span class="text-danger"><span class="text-muted">Int:</span> <?= htmlspecialchars($op['obs_interrupcao'], ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                                <?php if (empty($op['obs_aguardando']) && empty($op['obs_separacao']) && empty($op['obs_interrupcao'])): ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <span class="text-muted">Cad:</span> <?= htmlspecialchars($op['cadastrada_por_nome'], ENT_QUOTES, 'UTF-8') ?><br>
                                <?php if (!empty($op['separada_por_nome'])): ?>
                                    <span class="text-muted">Sep:</span> <strong><?= htmlspecialchars($op['separada_por_nome'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                <?php endif; ?>
                                <?php if (!empty($op['operador_retirada'])): ?>
                                    <span class="text-muted">Ret:</span> <strong><?= htmlspecialchars($op['operador_retirada'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                                <?php endif; ?>
                                <?php if (!empty($op['operador_interrupcao'])): ?>
                                    <span class="text-muted">Int:</span> <strong class="text-danger"><?= htmlspecialchars($op['operador_interrupcao'], ENT_QUOTES, 'UTF-8') ?></strong>
                                <?php endif; ?>
                            </td>
                            <td class="small text-nowrap">
                                <span class="text-muted">Cad:</span> <?= date('d/m H:i', strtotime($op['data_cadastro'])) ?><br>
                                <?php if (!empty($op['data_separacao'])): ?>
                                    <span class="text-muted">Sep:</span> <?= date('d/m H:i', strtotime($op['data_separacao'])) ?><br>
                                <?php endif; ?>
                                <?php if (!empty($op['data_retirada'])): ?>
                                    <span class="text-success"><span class="text-muted">Ret:</span> <?= date('d/m H:i', strtotime($op['data_retirada'])) ?></span><br>
                                <?php endif; ?>
                                <?php if (!empty($op['data_interrupcao'])): ?>
                                    <span class="text-danger"><span class="text-muted">Int:</span> <?= date('d/m H:i', strtotime($op['data_interrupcao'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($op['status'] === 'aguardando' && $podeSeparar): ?>
                                    <a href="separar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-primary">Separar</a>
                                <?php elseif (in_array($op['status'], ['separada', 'interrompida'], true) && $podeProduzir): ?>
                                    <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-success">Retirar</a>
                                <?php elseif ($op['status'] === 'retirada' && $podeProduzir): ?>
                                    <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-warning text-dark">Interromper</a>
                                    <form method="POST" action="finalizar_op.php" class="d-inline" onsubmit="return confirm('Confirmar que a OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?> foi totalmente produzida?');">
                                        <input type="hidden" name="id" value="<?= (int)$op['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Produzida</button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($ehAdmin): ?>
                                    <a href="editar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
                                    <form method="POST" action="excluir_op.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir a OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?>? Esta ação não pode ser desfeita.');">
                                        <input type="hidden" name="id" value="<?= (int)$op['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<footer class="text-center text-muted small mb-4">
    <p>Sistema de Controle de Ordens de Produção</p>
</footer>

</body>
</html>