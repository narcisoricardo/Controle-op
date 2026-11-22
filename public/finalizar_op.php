<?php

require_once __DIR__ . '/../app/controle_acesso.php';
exigirPerfil(['admin', 'producao']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opId = (int) ($_POST['id'] ?? 0);
if ($opId > 0) {
    $opController = new OpController($pdo);
    $opController->finalizar($opId);
}

header('Location: index.php');
exit;