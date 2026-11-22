<?php

require_once __DIR__ . '/../Models/OpModel.php';

class OpController
{
    private OpModel $opModel;

    public function __construct(PDO $pdo)
    {
        $this->opModel = new OpModel($pdo);
    }

    public function listar(): array
    {
        return $this->opModel->listarTodas();
    }

    public function buscarPorId(int $id): ?array
    {
        return $this->opModel->buscarPorId($id);
    }

    public function listarMaquinas(): array
    {
        return $this->opModel->listarMaquinas();
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

    public function separar(int $opId, array $post, int $usuarioId): ?string
    {
        $caixaTipo    = $post['caixa_tipo'] ?? '';
        $caixaNumero  = (int) ($post['caixa_numero'] ?? 0);
        $localizacao  = trim($post['localizacao'] ?? '');
        $anexoLink    = trim($post['anexo_link'] ?? '');
        $obsSeparacao = trim($post['obs_separacao'] ?? '');

        if (!in_array($caixaTipo, ['transparente', 'marrom'], true)) {
            return 'Selecione um tipo de caixa válido.';
        }

        if ($caixaNumero <= 0 || $localizacao === '') {
            return 'Informe o número da caixa e a localização no almoxarifado.';
        }

        if ($anexoLink !== '' && !filter_var($anexoLink, FILTER_VALIDATE_URL)) {
            return 'O link do anexo informado não é uma URL válida (ex: https://...).';
        }

        $this->opModel->registrarSeparacao([
            'id'            => $opId,
            'caixa_tipo'    => $caixaTipo,
            'caixa_numero'  => $caixaNumero,
            'localizacao'   => $localizacao,
            'anexo_link'    => $anexoLink !== '' ? $anexoLink : null,
            'obs_separacao' => $obsSeparacao !== '' ? $obsSeparacao : null,
            'separada_por'  => $usuarioId,
        ]);

        header('Location: index.php?separada=1');
        exit;
    }

    public function movimentar(int $opId, array $post): ?string
    {
        $tipo       = $post['tipo'] ?? '';
        $turno      = $post['turno'] ?? '';
        $maquinaId  = (int) ($post['maquina_id'] ?? 0);
        $observacao = trim($post['observacao'] ?? '');

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
            'op_id'      => $opId,
            'tipo'       => $tipo,
            'turno'      => $turno,
            'maquina_id' => $maquinaId > 0 ? $maquinaId : null,
            'observacao' => $observacao !== '' ? $observacao : null,
        ]);

        header('Location: index.php?movimentada=1');
        exit;
    }
}