<?php

require_once __DIR__ . '/../app/controle_acesso.php';
exigirPerfil(['admin']);

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/UsuarioController.php';

$usuarioController = new UsuarioController($pdo);
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar') {
        $erro = $usuarioController->cadastrar($_POST);
    } elseif ($acao === 'excluir') {
        $erro = $usuarioController->excluir((int)($_POST['id'] ?? 0), (int)$_SESSION['usuario_id']);
    }
}

$usuarios = $usuarioController->listar();

require __DIR__ . '/../app/Views/usuarios_view.php';