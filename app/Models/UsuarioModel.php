<?php

class UsuarioModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function buscarPorLogin(string $login): ?array
    {
        $sql = "SELECT id, nome, login, senha_hash, perfil 
                FROM usuarios 
                WHERE login = :login 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':login' => $login]);

        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT id, nome, login, perfil FROM usuarios ORDER BY nome ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function criar(string $nome, string $login, string $senha, string $perfil): bool
    {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, login, senha_hash, perfil) 
                VALUES (:nome, :login, :senha_hash, :perfil)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nome'       => $nome,
            ':login'      => $login,
            ':senha_hash' => $senhaHash,
            ':perfil'     => $perfil,
        ]);
    }

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}