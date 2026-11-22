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
                    o.anexo_link,
                    o.obs_separacao,
                    u.nome AS cadastrada_por_nome
                FROM ops o
                INNER JOIN usuarios u ON o.cadastrada_por = u.id
                ORDER BY o.data_cadastro DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT id, numero_op, status, obs_aguardando 
                FROM ops 
                WHERE id = :id 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $op = $stmt->fetch();
        return $op ?: null;
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

    public function registrarSeparacao(array $dados): bool
    {
        $sql = "UPDATE ops SET 
                    status = 'separada',
                    caixa_tipo = :caixa_tipo,
                    caixa_numero = :caixa_numero,
                    localizacao = :localizacao,
                    anexo_link = :anexo_link,
                    obs_separacao = :obs_separacao,
                    separada_por = :separada_por,
                    data_separacao = NOW()
                WHERE id = :id AND status = 'aguardando'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':caixa_tipo'    => $dados['caixa_tipo'],
            ':caixa_numero'  => $dados['caixa_numero'],
            ':localizacao'   => $dados['localizacao'],
            ':anexo_link'    => $dados['anexo_link'],
            ':obs_separacao' => $dados['obs_separacao'],
            ':separada_por'  => $dados['separada_por'],
            ':id'            => $dados['id'],
        ]);
    }
}