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
        $sql = "SELECT 
                    id, 
                    numero_op, 
                    status, 
                    obs_aguardando, 
                    caixa_tipo, 
                    caixa_numero, 
                    localizacao, 
                    obs_separacao 
                FROM ops 
                WHERE id = :id 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $op = $stmt->fetch();
        return $op ?: null;
    }

    public function listarMaquinas(): array
    {
        $sql = "SELECT id, nome FROM maquinas ORDER BY id ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function listarMovimentacoesPorOp(int $opId): array
    {
        $sql = "SELECT 
                    mov.tipo,
                    mov.turno,
                    mov.observacao,
                    mov.data_hora,
                    maq.nome AS maquina_nome
                FROM movimentacoes mov
                LEFT JOIN maquinas maq ON mov.maquina_id = maq.id
                WHERE mov.op_id = :op_id
                ORDER BY mov.data_hora DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':op_id' => $opId]);
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

    public function registrarMovimentacao(array $dados): bool
    {
        $novoStatus = $dados['tipo'] === 'retirada' ? 'retirada' : 'interrompida';

        try {
            $this->pdo->beginTransaction();

            $sqlMov = "INSERT INTO movimentacoes (op_id, tipo, turno, maquina_id, observacao)
                       VALUES (:op_id, :tipo, :turno, :maquina_id, :observacao)";
            $stmtMov = $this->pdo->prepare($sqlMov);
            $stmtMov->execute([
                ':op_id'      => $dados['op_id'],
                ':tipo'       => $dados['tipo'],
                ':turno'      => $dados['turno'],
                ':maquina_id' => $dados['maquina_id'],
                ':observacao' => $dados['observacao'],
            ]);

            $sqlOp = "UPDATE ops SET status = :status WHERE id = :id";
            $stmtOp = $this->pdo->prepare($sqlOp);
            $stmtOp->execute([
                ':status' => $novoStatus,
                ':id'     => $dados['op_id'],
            ]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}