<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark navbar-industrial px-4 py-2">
    <a href="index.php" class="navbar-brand mb-0">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</nav>

<div class="container mt-4 mb-5">
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Usuário cadastrado com sucesso!</div>
    <?php endif; ?>

    <?php if (isset($_GET['excluido'])): ?>
        <div class="alert alert-warning">Usuário removido com sucesso.</div>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card-sistema p-4">
                <h5 class="fw-bold mb-3">Novo Usuário de Acesso</h5>

                <form method="POST" action="usuarios.php">
                    <input type="hidden" name="acao" value="criar">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome / Setor</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Terminal Almoxarifado 2" required>
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Ex: almox2" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha (mín. 6 caracteres)</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>

                    <div class="mb-4">
                        <label for="perfil" class="form-label">Perfil de Permissão</label>
                        <select class="form-select" id="perfil" name="perfil" required>
                            <option value="">Selecione...</option>
                            <option value="almoxarife">Almoxarife (Separação)</option>
                            <option value="producao">Produção (Retirada / Interrupção)</option>
                            <option value="admin">Administrador (Acesso Total)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cadastrar Usuário</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card-sistema">
                <div class="table-responsive">
                    <table class="table tabela-ops align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>Perfil</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="fw-semibold"><?= htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><code><?= htmlspecialchars($u['login'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                    <td>
                                        <span class="badge bg-secondary text-uppercase">
                                            <?= htmlspecialchars($u['perfil'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if ((int)$u['id'] !== (int)$_SESSION['usuario_id']): ?>
                                            <form method="POST" action="usuarios.php" class="d-inline" onsubmit="return confirm('Excluir o usuário <?= htmlspecialchars($u['login'], ENT_QUOTES, 'UTF-8') ?>?');">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">Atual</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>