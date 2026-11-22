<?php

require __DIR__ . '/../config/conexao.php';

$stmt = $pdo->query('SELECT nome, setor FROM maquinas ORDER BY nome LIMIT 5');
$maquinas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Conexão - Controle de OP</title>
</head>
<body>
    <h1>Conectado com sucesso!</h1>
    <p>Primeiras 5 máquinas cadastradas:</p>
    <ul>
        <?php foreach ($maquinas as $m): ?>
            <li><?= htmlspecialchars($m['nome']) ?> (<?= htmlspecialchars($m['setor']) ?>)</li>
        <?php endforeach; ?>
    </ul>
</body>
</html>