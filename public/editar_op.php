<?php

require_once __DIR__ . '/../app/controle_acesso.php';
// Apenas o perfil Admin pode editar uma OP cadastrada
exigirPerfil(['admin']);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opId = (int) ($_GET['id'] ?? 0);
$opController = new OpController($pdo);
$op = $opController->buscarPorId($opId);

if (!$op) {
    header('Location: index.php');
    exit;
}

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = $opController->editar($opId, $_POST);
}

require __DIR__ . '/../app/Views/editar_op_view.php';