<?php

require_once __DIR__ . '/../Models/UsuarioModel.php';

class LoginController
{
    private UsuarioModel $usuarioModel;

    public function __construct(PDO $pdo)
    {
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function login(string $login, string $senha): ?string
    {
        $login = trim($login);

        if ($login === '' || $senha === '') {
            return 'Preencha o login e a senha.';
        }

        $usuario = $this->usuarioModel->buscarPorLogin($login);

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            return 'Usuário ou senha inválidos.';
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nome']   = $usuario['nome'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];

        header('Location: index.php');
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: login.php');
        exit;
    }
}