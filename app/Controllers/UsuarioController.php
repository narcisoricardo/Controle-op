<?php

require_once __DIR__ . '/../Models/UsuarioModel.php';

class UsuarioController
{
    private UsuarioModel $usuarioModel;

    public function __construct(PDO $pdo)
    {
        $this->usuarioModel = new UsuarioModel($pdo);
    }

    public function listar(): array
    {
        return $this->usuarioModel->listarTodos();
    }

    public function cadastrar(array $post): ?string
    {
        $nome   = trim($post['nome'] ?? '');
        $login  = trim($post['login'] ?? '');
        $senha  = $post['senha'] ?? '';
        $perfil = $post['perfil'] ?? '';

        if ($nome === '' || $login === '' || $senha === '') {
            return 'Preencha nome, login e senha.';
        }

        if (strlen($senha) < 6) {
            return 'A senha deve ter pelo menos 6 caracteres.';
        }

        if (!in_array($perfil, ['admin', 'almoxarife', 'producao'], true)) {
            return 'Selecione um perfil de acesso válido.';
        }

        try {
            $this->usuarioModel->criar($nome, $login, $senha, $perfil);
            header('Location: usuarios.php?sucesso=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'Já existe um usuário cadastrado com esse login.';
            }

            return 'Erro ao cadastrar usuário.';
        }
    }

    public function excluir(int $id, int $usuarioLogadoId): ?string
    {
        if ($id === $usuarioLogadoId) {
            return 'Você não pode excluir o próprio usuário logado.';
        }

        $this->usuarioModel->excluir($id);
        header('Location: usuarios.php?excluido=1');
        exit;
    }
}