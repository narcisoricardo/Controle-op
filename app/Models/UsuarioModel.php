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
                WHERE login = :login AND ativo = 1 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':login' => $login]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }
}