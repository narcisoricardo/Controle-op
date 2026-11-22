<?php

require_once __DIR__ . '/../app/controle_acesso.php';
exigirLogin();

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$filtros = [
    'busca'   => trim($_GET['busca'] ?? ''),
    'status'  => $_GET['status'] ?? '',
    'periodo' => $_GET['periodo'] ?? '',
];

$opController = new OpController($pdo);
$ops = $opController->listar($filtros);

// Calcula indicadores (KPIs) com base nos registros filtrados
$resumo = [
    'total'        => count($ops),
    'aguardando'   => 0,
    'separada'     => 0,
    'retirada'     => 0,
    'interrompida' => 0,
    'produzida'    => 0,
];

foreach ($ops as $op) {
    if (isset($resumo[$op['status']])) {
        $resumo[$op['status']]++;
    }
}

require __DIR__ . '/../app/Views/painel_view.php';