<?php

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../app/Controllers/LoginController.php';

$controller = new LoginController($pdo);
$controller->logout();