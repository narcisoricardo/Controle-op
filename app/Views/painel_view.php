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
    <div class="alert alert-success">
        Autenticação concluída com sucesso! Seu perfil atual é
        <strong><?= htmlspecialchars($_SESSION['usuario_perfil'], ENT_QUOTES, 'UTF-8') ?></strong>.
    </div>
</div>

</body>
</html>