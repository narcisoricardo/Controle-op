<?php

require_once __DIR__ . '/../Models/OpModel.php';

class OpController
{
    private OpModel $opModel;

    public function __construct(PDO $pdo)
    {
        $this->opModel = new OpModel($pdo);
    }

   public function listar(array $filtros = []): array
    {
        return $this->opModel->listarTodas($filtros);
    }

    public function buscarPorId(int $id): ?array
    {
        return $this->opModel->buscarPorId($id);
    }

    public function listarMaquinas(): array
    {
        return $this->opModel->listarMaquinas();
    }

    public function listarOperadores(): array
    {
        return $this->opModel->listarNomesOperadores();
    }

    public function listarHistorico(int $opId): array
    {
        return $this->opModel->listarMovimentacoesPorOp($opId);
    }

    public function cadastrar(array $post, int $usuarioId): ?string
    {
        $numeroOp      = trim($post['numero_op'] ?? '');
        $obsAguardando = trim($post['obs_aguardando'] ?? '');

        if ($numeroOp === '') {
            return 'Informe o número da Ordem de Produção.';
        }

        try {
            $this->opModel->criar($numeroOp, $obsAguardando, $usuarioId);

            header('Location: index.php?sucesso=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'Já existe uma Ordem de Produção cadastrada com esse número.';
            }

            return 'Erro ao salvar a Ordem de Produção. Tente novamente.';
        }
    }

    public function editar(int $opId, array $post): ?string
    {
        $numeroOp      = trim($post['numero_op'] ?? '');
        $obsAguardando = trim($post['obs_aguardando'] ?? '');

        if ($numeroOp === '') {
            return 'Informe o número da Ordem de Produção.';
        }

        try {
            $this->opModel->atualizar($opId, $numeroOp, $obsAguardando);

            header('Location: index.php?editada=1');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return 'Já existe outra Ordem de Produção cadastrada com esse número.';
            }

            return 'Erro ao atualizar a Ordem de Produção. Tente novamente.';
        }
    }

    public function separar(int $opId, array $post, array $files, int $usuarioId): ?string
    {
        $separadaPorNome = trim($post['separada_por_nome'] ?? '');
        $caixaTipo       = $post['caixa_tipo'] ?? '';
        $caixaNumero     = (int) ($post['caixa_numero'] ?? 0);
        $localizacao     = trim($post['localizacao'] ?? '');
        $obsSeparacao    = trim($post['obs_separacao'] ?? '');

        if ($separadaPorNome === '') {
            return 'Informe o nome do operador que separou a OP.';
        }

        if (!in_array($caixaTipo, ['transparente', 'marrom'], true)) {
            return 'Selecione um tipo de caixa válido.';
        }

        if ($caixaNumero <= 0 || $localizacao === '') {
            return 'Informe o número da caixa e a localização no almoxarifado.';
        }

        $caminhoAnexo = null;

        if (isset($files['anexo_arquivo']) && $files['anexo_arquivo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $arquivo = $files['anexo_arquivo'];

            if ($arquivo['error'] !== UPLOAD_ERR_OK) {
                return 'Erro ao enviar o arquivo anexado.';
            }

            if ($arquivo['size'] > 5 * 1024 * 1024) {
                return 'O arquivo ultrapassa o limite máximo de 5 MB.';
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeReal = $finfo->file($arquivo['tmp_name']);

            $tiposPermitidos = [
                'application/pdf' => 'pdf',
                'image/jpeg'      => 'jpg',
                'image/png'       => 'png',
            ];

            if (!isset($tiposPermitidos[$mimeReal])) {
                return 'Formato inválido. Envie apenas PDF, JPG ou PNG.';
            }

            $extensao = $tiposPermitidos[$mimeReal];
            $nomeFinal = bin2hex(random_bytes(16)) . '.' . $extensao;
            $pastaDestino = __DIR__ . '/../../public/uploads/anexos/';

            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0755, true);
            }

            if (!move_uploaded_file($arquivo['tmp_name'], $pastaDestino . $nomeFinal)) {
                return 'Não foi possível salvar o arquivo no servidor.';
            }

            $caminhoAnexo = 'uploads/anexos/' . $nomeFinal;
        }

        $this->opModel->registrarSeparacao([
            'id'                => $opId,
            'caixa_tipo'        => $caixaTipo,
            'caixa_numero'      => $caixaNumero,
            'localizacao'       => $localizacao,
            'anexo_link'        => $caminhoAnexo,
            'obs_separacao'     => $obsSeparacao !== '' ? $obsSeparacao : null,
            'separada_por'      => $usuarioId,
            'separada_por_nome' => $separadaPorNome,
        ]);

        header('Location: index.php?separada=1');
        exit;
    }

    public function movimentar(int $opId, array $post): ?string
    {
        $operadorNome = trim($post['operador_nome'] ?? '');
        $tipo         = $post['tipo'] ?? '';
        $turno        = $post['turno'] ?? '';
        $maquinaId    = (int) ($post['maquina_id'] ?? 0);
        $observacao   = trim($post['observacao'] ?? '');

        if ($operadorNome === '') {
            return 'Informe o nome do operador responsável.';
        }

        if (!in_array($tipo, ['retirada', 'interrupcao'], true)) {
            return 'Selecione um tipo de movimentação válido.';
        }

        if (!in_array($turno, ['primeiro', 'segundo', 'terceiro'], true)) {
            return 'Selecione o turno da operação.';
        }

        if ($tipo === 'retirada' && $maquinaId <= 0) {
            return 'Para retirar a OP, selecione a injetora de destino.';
        }

        if ($tipo === 'interrupcao' && $observacao === '') {
            return 'Para interromper a OP, informe o motivo na observação.';
        }

        $this->opModel->registrarMovimentacao([
            'op_id'         => $opId,
            'tipo'          => $tipo,
            'turno'         => $turno,
            'maquina_id'    => $maquinaId > 0 ? $maquinaId : null,
            'operador_nome' => $operadorNome,
            'observacao'    => $observacao !== '' ? $observacao : null,
        ]);

        header('Location: index.php?movimentada=1');
        exit;
    }

    public function finalizar(int $opId): void
    {
        $this->opModel->finalizarProducao($opId);
        header('Location: index.php?produzida=1');
        exit;
    }

    public function excluir(int $opId): void
    {
        $this->opModel->excluir($opId);
        header('Location: index.php?excluida=1');
        exit;
    }
}