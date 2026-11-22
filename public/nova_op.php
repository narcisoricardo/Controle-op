<?php

require_once __DIR__ . '/../app/controle_acesso.php';
exigirPerfil(['admin']);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opController = new OpController($pdo);
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = $opController->cadastrar($_POST, (int)$_SESSION['usuario_id']);
}

require __DIR__ . '/../app/Views/nova_op_view.php';