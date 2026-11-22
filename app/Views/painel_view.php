<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand mb-0 h1">Controle de OP</span>
    <div class="d-flex align-items-center text-white gap-3">
        <span>
            Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
            <span class="badge bg-secondary text-uppercase ms-1">
                <?= htmlspecialchars($_SESSION['usuario_perfil'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
    </div>
</nav>

<div class="container mt-4">
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Ordens de Produção</h4>

        <?php if (in_array($_SESSION['usuario_perfil'], ['admin', 'producao'], true)): ?>
            <a href="nova_op.php" class="btn btn-primary">+ Nova OP</a>
        <?php endif; ?>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nº OP</th>
                        <th>Status</th>
                        <th>Caixa / Localização</th>
                        <th>Observações</th>
                        <th>Cadastrada por</th>
                        <th>Data Cadastro</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ops)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Nenhuma Ordem de Produção cadastrada até o momento.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $coresStatus = [
                            'aguardando'   => 'warning text-dark',
                            'separada'     => 'info text-dark',
                            'retirada'     => 'success',
                            'interrompida' => 'danger',
                        ];
                        $podeSeparar  = in_array($_SESSION['usuario_perfil'], ['admin', 'almoxarife'], true);
                        $podeProduzir = in_array($_SESSION['usuario_perfil'], ['admin', 'producao'], true);
                        ?>
                        <?php foreach ($ops as $op): ?>
                            <?php $cor = $coresStatus[$op['status']] ?? 'secondary'; ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="badge bg-<?= $cor ?>">
                                        <?= htmlspecialchars(strtoupper($op['status']), ENT_QUOTES, 'UTF-8') ?>
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
                                <td>
                                    <?php if (!empty($op['obs_aguardando'])): ?>
                                        <div><small class="text-muted">Cad:</small> <?= htmlspecialchars($op['obs_aguardando'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($op['obs_separacao'])): ?>
                                        <div><small class="text-muted">Sep:</small> <?= htmlspecialchars($op['obs_separacao'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endif; ?>
                                    <?php if (empty($op['obs_aguardando']) && empty($op['obs_separacao'])): ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($op['cadastrada_por_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($op['data_cadastro'])) ?></td>
                                <td class="text-end">
                                    <?php if ($op['status'] === 'aguardando' && $podeSeparar): ?>
                                        <a href="separar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-info text-dark">
                                            Separar
                                        </a>
                                    <?php elseif (in_array($op['status'], ['separada', 'interrompida'], true) && $podeProduzir): ?>
                                        <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-success">
                                            Retirar
                                        </a>
                                    <?php elseif ($op['status'] === 'retirada' && $podeProduzir): ?>
                                        <a href="movimentar_op.php?id=<?= (int)$op['id'] ?>" class="btn btn-sm btn-outline-danger">
                                            Interromper
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">—</span>
                                    <?php endif; ?>
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