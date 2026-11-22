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
                        <th>Observação</th>
                        <th>Cadastrada por</th>
                        <th>Data Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ops)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
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
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($op['obs_aguardando'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($op['cadastrada_por_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($op['data_cadastro'])) ?></td>
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