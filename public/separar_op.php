<?php

require_once __DIR__ . '/../app/controle_acesso.php';
// Apenas Admin e Almoxarife podem registrar separação
exigirPerfil(['admin', 'almoxarife']);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opId = (int) ($_GET['id'] ?? 0);
$opController = new OpController($pdo);
$op = $opController->buscarPorId($opId);

if (!$op || $op['status'] !== 'aguardando') {
    header('Location: index.php');
    exit;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = $opController->separar($opId, $_POST, (int)$_SESSION['usuario_id']);
}

require __DIR__ . '/../app/Views/separar_op_view.php';