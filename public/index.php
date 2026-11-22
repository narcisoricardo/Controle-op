<?php

require_once __DIR__ . '/../app/controle_acesso.php';
exigirLogin();

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/OpController.php';

$opController = new OpController($pdo);
$ops = $opController->listar();

require __DIR__ . '/../app/Views/painel_view.php';
