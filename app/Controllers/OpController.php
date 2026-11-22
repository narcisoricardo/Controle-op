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
}