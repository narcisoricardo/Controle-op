<?php

require_once __DIR__ . '/../app/controle_acesso.php';
// Apenas Admin e Produção podem retirar ou interromper OPs
exigirPerfil(['admin', 'producao']);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opId = (int) ($_GET['id'] ?? 0);
$opController = new OpController($pdo);
$op = $opController->buscarPorId($opId);

// Só permite movimentar OPs que já foram separadas, estão retiradas ou interrompidas
if (!$op || !in_array($op['status'], ['separada', 'retirada', 'interrompida'], true)) {
    header('Location: index.php');
    exit;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = $opController->movimentar($opId, $_POST);
}

$maquinas  = $opController->listarMaquinas();
$historico = $opController->listarHistorico($opId);

require __DIR__ . '/../app/Views/movimentar_op_view.php';