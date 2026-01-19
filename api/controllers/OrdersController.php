<?php

namespace api\controllers;

use Yii;
use common\models\Encomenda;
use common\models\EncomendaLinha;
use common\models\Fornecedor;
use common\models\Stock;
use common\models\Movimento;
use common\models\Material;

class OrdersController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (isset($behaviors['authenticator'])) {
            $behaviors['authenticator']['except'] = ['options'];
        }
        return $behaviors;
    }

    // GET /orders?limit=50
    public function actionIndex($limit = 50)
    {
        $limit = min((int)$limit, 100);
        $encomendas = Encomenda::find()
            ->orderBy(['data_encomenda' => SORT_DESC])
            ->limit($limit)
            ->all();
        $result = [];
        foreach ($encomendas as $e) {
            $result[] = [
                'id' => (int)$e->id,
                'numero' => 'ENC-' . $e->id,
                'fornecedor' => $e->fornecedor ? $e->fornecedor->nome_fornecedor : null,
                'estado' => $e->estado ? strtolower($e->estado) : null,
                'data' => $e->data_encomenda,
            ];
        }
        return $result;
    }

    // GET /orders/{id}
    public function actionView($id)
    {
        $e = Encomenda::find()->where(['id' => $id])->one();
        if (!$e) {
            return $this->error('not_found', 'Encomenda não encontrada.', 404);
        }
        $linhas = [];
        foreach ($e->encomendaLinhas as $linha) {
            $materialCodigo = $linha->material ? $linha->material->codigo : null;
            $descricao = $linha->material ? $linha->material->nome_material : $linha->nome_material;
            $linhas[] = [
                'id' => (int)$linha->id,
                'encomendaId' => (int)$linha->encomenda_id,
                'materialCodigo' => $materialCodigo,
                'descricao' => $descricao,
                'quantidade' => (int)$linha->quantidade,
            ];
        }
        return [
            'id' => (int)$e->id,
            'numero' => 'ENC-' . $e->id,
            'fornecedor' => $e->fornecedor ? $e->fornecedor->nome_fornecedor : null,
            'estado' => $e->estado ? strtolower($e->estado) : null,
            'data' => $e->data_encomenda,
            'linhas' => $linhas,
        ];
    }

    // POST /orders/{id}/receive
    public function actionReceive($id)
    {
        $e = Encomenda::find()->where(['id' => $id])->one();
        if (!$e) {
            return $this->error('not_found', 'Encomenda não encontrada.', 404);
        }
        if (strtolower($e->estado) !== 'pendente') {
            return $this->error('invalid', 'Só pode receber encomendas pendentes.', 400);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $e->estado = 'Recebida';
            $e->data_rececao = date('Y-m-d H:i:s');
            $e->save(false);
            foreach ($e->encomendaLinhas as $linha) {
                if (!$linha->material_id) continue;
                $stock = Stock::find()->where(['material_id' => $linha->material_id])->one();
                if (!$stock) {
                    $stock = new Stock();
                    $stock->material_id = $linha->material_id;
                    $stock->quantidade_atual = 0;
                }
                $stock->quantidade_atual += $linha->quantidade;
                $stock->save(false);
                $mov = new Movimento();
                $mov->material_id = $linha->material_id;
                $mov->user_id = Yii::$app->user->id;
                $mov->tipo = 'Entrada';
                $mov->quantidade = $linha->quantidade;
                $mov->data_movimentacao = date('Y-m-d H:i:s');
                $mov->origem = 'Receção encomenda ENC-' . $e->id;
                $mov->save(false);
            }
            $transaction->commit();
        } catch (\Exception $ex) {
            $transaction->rollBack();
            return $this->error('receive_failed', 'Erro ao receber encomenda.', 500, $ex->getMessage());
        }
        return [
            'ok' => true,
            'orderId' => (int)$e->id,
            'estado' => 'recebida',
        ];
    }
}
