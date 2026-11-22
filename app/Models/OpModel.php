<?php

class OpModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodas(array $filtros = []): array
    {
        $sql = "SELECT 
                    o.id,
                    o.numero_op,
                    o.status,
                    o.obs_aguardando,
                    o.data_cadastro,
                    o.data_separacao,
                    o.caixa_tipo,
                    o.caixa_numero,
                    o.localizacao,
                    o.anexo_link,
                    o.obs_separacao,
                    o.separada_por_nome,
                    u.nome AS cadastrada_por_nome,
                    (
                        SELECT m.operador_nome 
                        FROM movimentacoes m 
                        WHERE m.op_id = o.id AND m.tipo = 'retirada'
                        ORDER BY m.data_hora DESC, m.id DESC 
                        LIMIT 1
                    ) AS operador_retirada,
                    (
                        SELECT m.data_hora 
                        FROM movimentacoes m 
                        WHERE m.op_id = o.id AND m.tipo = 'retirada'
                        ORDER BY m.data_hora DESC, m.id DESC 
                        LIMIT 1
                    ) AS data_retirada,
                    (
                        SELECT m.operador_nome 
                        FROM movimentacoes m 
                        WHERE m.op_id = o.id AND m.tipo = 'interrupcao'
                        ORDER BY m.data_hora DESC, m.id DESC 
                        LIMIT 1
                    ) AS operador_interrupcao,
                    (
                        SELECT m.data_hora 
                        FROM movimentacoes m 
                        WHERE m.op_id = o.id AND m.tipo = 'interrupcao'
                        ORDER BY m.data_hora DESC, m.id DESC 
                        LIMIT 1
                    ) AS data_interrupcao,
                    (
                        SELECT m.observacao 
                        FROM movimentacoes m 
                        WHERE m.op_id = o.id AND m.tipo = 'interrupcao'
                        ORDER BY m.data_hora DESC, m.id DESC 
                        LIMIT 1
                    ) AS obs_interrupcao
                FROM ops o
                INNER JOIN usuarios u ON o.cadastrada_por = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['busca'])) {
            $sql .= " AND o.numero_op LIKE :busca";
            $params[':busca'] = '%' . $filtros['busca'] . '%';
        }

        $statusValidos = ['aguardando', 'separada', 'retirada', 'interrompida', 'produzida'];
        if (!empty($filtros['status']) && in_array($filtros['status'], $statusValidos, true)) {
            $sql .= " AND o.status = :status";
            $params[':status'] = $filtros['status'];
        }

        $periodo = $filtros['periodo'] ?? '';
        if ($periodo === 'hoje') {
            $sql .= " AND DATE(o.data_cadastro) = CURDATE()";
        } elseif ($periodo === 'semana') {
            $sql .= " AND o.data_cadastro >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        } elseif ($periodo === 'mes') {
            $sql .= " AND YEAR(o.data_cadastro) = YEAR(CURDATE()) AND MONTH(o.data_cadastro) = MONTH(CURDATE())";
        }

        $sql .= " ORDER BY o.data_cadastro DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
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
                    anexo_link,
                    obs_separacao,
                    separada_por_nome
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

    public function listarNomesOperadores(): array
    {
        $sql = "SELECT DISTINCT separada_por_nome AS nome FROM ops WHERE separada_por_nome IS NOT NULL
                UNION
                SELECT DISTINCT operador_nome AS nome FROM movimentacoes WHERE operador_nome IS NOT NULL
                ORDER BY nome ASC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function listarMovimentacoesPorOp(int $opId): array
    {
        $sql = "SELECT 
                    mov.tipo,
                    mov.turno,
                    mov.operador_nome,
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

    public function atualizar(int $id, string $numeroOp, ?string $obsAguardando): bool
    {
        $sql = "UPDATE ops SET 
                    numero_op = :numero_op,
                    obs_aguardando = :obs_aguardando
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':numero_op'      => $numeroOp,
            ':obs_aguardando' => $obsAguardando !== '' ? $obsAguardando : null,
            ':id'             => $id,
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
                    separada_por_nome = :separada_por_nome,
                    data_separacao = NOW()
                WHERE id = :id AND status = 'aguardando'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':caixa_tipo'        => $dados['caixa_tipo'],
            ':caixa_numero'      => $dados['caixa_numero'],
            ':localizacao'       => $dados['localizacao'],
            ':anexo_link'        => $dados['anexo_link'],
            ':obs_separacao'     => $dados['obs_separacao'],
            ':separada_por'      => $dados['separada_por'],
            ':separada_por_nome' => $dados['separada_por_nome'],
            ':id'                => $dados['id'],
        ]);
    }

    public function registrarMovimentacao(array $dados): bool
    {
        $novoStatus = $dados['tipo'] === 'retirada' ? 'retirada' : 'interrompida';

        try {
            $this->pdo->beginTransaction();

            $sqlMov = "INSERT INTO movimentacoes (op_id, tipo, turno, maquina_id, operador_nome, observacao)
                       VALUES (:op_id, :tipo, :turno, :maquina_id, :operador_nome, :observacao)";
            $stmtMov = $this->pdo->prepare($sqlMov);
            $stmtMov->execute([
                ':op_id'         => $dados['op_id'],
                ':tipo'          => $dados['tipo'],
                ':turno'         => $dados['turno'],
                ':maquina_id'    => $dados['maquina_id'],
                ':operador_nome' => $dados['operador_nome'],
                ':observacao'    => $dados['observacao'],
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

    public function finalizarProducao(int $id): bool
    {
        $sql = "UPDATE ops SET status = 'produzida' WHERE id = :id AND status = 'retirada'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function excluir(int $id): bool
    {
        $op = $this->buscarPorId($id);
        if (!$op) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            $stmtMov = $this->pdo->prepare("DELETE FROM movimentacoes WHERE op_id = :id");
            $stmtMov->execute([':id' => $id]);

            $stmtOp = $this->pdo->prepare("DELETE FROM ops WHERE id = :id");
            $stmtOp->execute([':id' => $id]);

            $this->pdo->commit();

            if (!empty($op['anexo_link'])) {
                $caminhoArquivo = __DIR__ . '/../../public/' . $op['anexo_link'];
                if (is_file($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
            }

            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}