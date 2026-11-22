<?php

session_start();

// Se o usuário já estiver logado, vai direto para o painel
if (!empty($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/LoginController.php';

$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new LoginController($pdo);
    $erro = $controller->login(
        $_POST['login'] ?? '',
        $_POST['senha'] ?? ''
    );
}

require __DIR__ . '/../app/Views/login_view.php';
