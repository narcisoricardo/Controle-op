<?php

class OpModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodas(): array
    {
        $sql = "SELECT 
                    o.id,
                    o.numero_op,
                    o.status,
                    o.obs_aguardando,
                    o.data_cadastro,
                    o.caixa_tipo,
                    o.caixa_numero,
                    o.localizacao,
                    u.nome AS cadastrada_por_nome
                FROM ops o
                INNER JOIN usuarios u ON o.cadastrada_por = u.id
                ORDER BY o.data_cadastro DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function criar(string $numeroOp, ?string $obsAguardando, int $usuarioId): bool
    {
        $sql = "INSERT INTO ops (numero_op, obs_aguardando, cadastrada_por) 
                VALUES (:numero_op, :obs_aguardando, :cadastrada_por)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':numero_op'      => $numeroOp,
            ':obs_aguardando' => $obsAguardando !== '' ? $obsAguardando : null,
            ':cadastrada_por' => $usuarioId,
        ]);
    }
}