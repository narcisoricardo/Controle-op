<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirLogin(): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
}

function exigirPerfil(array $perfisPermitidos): void
{
    exigirLogin();

    if (!in_array($_SESSION['usuario_perfil'], $perfisPermitidos, true)) {
        http_response_code(403);
        exit('Acesso negado: seu perfil não tem permissão para acessar esta área.');
    }
}
