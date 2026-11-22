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
}