<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Separar OP - Controle de OP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
</head>
<body>

<header class="navbar navbar-dark navbar-industrial px-4 py-2">
    <a href="index.php" class="navbar-brand fs-5 mb-0">Controle de OP</a>
    <a href="index.php" class="btn btn-outline-light btn-sm">Voltar ao Painel</a>
</header>

<main class="container mt-4">
    <section class="row justify-content-center">
        <article class="col-md-6 card-sistema p-4">
            <h1 class="h4 fw-bold mb-1">Separar OP #<?= htmlspecialchars($op['numero_op'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-muted small mb-3">
                Obs. do cadastro: <?= htmlspecialchars($op['obs_aguardando'] ?? 'Nenhuma', ENT_QUOTES, 'UTF-8') ?>
            </p>

            <?php if (!empty($erro)): ?>
                <p class="alert alert-danger py-2">
                    <strong>Erro:</strong> <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="POST" action="separar_op.php?id=<?= (int)$op['id'] ?>" enctype="multipart/form-data">
                <p class="mb-3">
                    <label for="separada_por_nome" class="form-label">Separado por (Nome do Operador)</label>
                    <input type="text" class="form-control" id="separada_por_nome" name="separada_por_nome" list="lista_operadores" placeholder="Digite seu nome..." required autofocus>
                    <datalist id="lista_operadores">
                        <?php foreach ($operadores as $nomeOp): ?>
                            <option value="<?= htmlspecialchars($nomeOp, ENT_QUOTES, 'UTF-8') ?>">
                        <?php endforeach; ?>
                    </datalist>
                </p>

                <p class="mb-3">
                    <label for="caixa_tipo" class="form-label">Tipo de Caixa</label>
                    <select class="form-select" id="caixa_tipo" name="caixa_tipo" required>
                        <option value="">Selecione...</option>
                        <option value="transparente">Transparente</option>
                        <option value="marrom">Marrom</option>
                    </select>
                </p>

                <p class="mb-3">
                    <label for="caixa_numero" class="form-label">Número da Caixa</label>
                    <input type="number" class="form-control" id="caixa_numero" name="caixa_numero" min="1" placeholder="Ex: 12" required>
                </p>

                <p class="mb-3">
                    <label for="localizacao" class="form-label">Localização Física</label>
                    <input type="text" class="form-control" id="localizacao" name="localizacao" placeholder="Ex: Prateleira B2 - Corredor 3" required>
                </p>

                <p class="mb-3">
                    <label for="anexo_arquivo" class="form-label">Anexar Laudo / Documento (opcional)</label>
                    <input type="file" class="form-control" id="anexo_arquivo" name="anexo_arquivo" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">Formatos aceitos: PDF, JPG ou PNG (máx. 5 MB).</small>
                </p>

                <p class="mb-4">
                    <label for="obs_separacao" class="form-label">Observação da Separação (opcional)</label>
                    <textarea class="form-control" id="obs_separacao" name="obs_separacao" rows="2"></textarea>
                </p>

                <p class="text-end mb-0">
                    <a href="index.php" class="btn btn-secondary me-1">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Confirmar Separação</button>
                </p>
            </form>
        </article>
    </section>
</main>

<footer class="text-center text-muted small mt-5">
    <p>Sistema de Controle de Ordens de Produção</p>
</footer>

</body>
</html>