<?php

namespace api\controllers;

use Yii;
use common\models\Movimento;
use common\models\Material;
use common\models\Stock;
use yii\web\BadRequestHttpException;

class MovementsController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (isset($behaviors['authenticator'])) {
            $behaviors['authenticator']['except'] = ['options'];
        }
        return $behaviors;
    }

    // GET /movements?limit=100&from=YYYY-MM-DD&to=YYYY-MM-DD
    public function actionIndex($limit = 100, $from = null, $to = null)
    {
        $query = Movimento::find()->joinWith('material');
        if ($from) {
            $query->andWhere(['>=', 'data_movimentacao', $from . ' 00:00:00']);
        }
        if ($to) {
            $query->andWhere(['<=', 'data_movimentacao', $to . ' 23:59:59']);
        }
        $limit = min((int)$limit, 200);
        $movs = $query->orderBy(['data_movimentacao' => SORT_DESC])->limit($limit)->all();
        $result = [];
        foreach ($movs as $mov) {
            $result[] = [
                'id' => $mov->id,
                'tipo' => strtolower($mov->tipo),
                'quantidade' => (int)$mov->quantidade,
                'data' => $mov->data_movimentacao,
                'materialCodigo' => $mov->material ? $mov->material->codigo : null,
                'observacoes' => $mov->origem !== null ? $mov->origem : null,
            ];
        }
        return $result;
    }

    // POST /movements/out
    public function actionOut()
    {
        $body = Yii::$app->request->getBodyParams();
        $codigo = $body['materialCodigo'] ?? null;
        $quantidade = $body['quantidade'] ?? null;
        $observacoes = $body['observacoes'] ?? null;

        if (!$codigo) {
            return $this->error('invalid', 'materialCodigo obrigatório.', 400);
        }
        $material = Material::find()->where(['codigo' => $codigo])->one();
        if (!$material) {
            return $this->error('not_found', 'Material não encontrado.', 404);
        }
        if (!is_numeric($quantidade) || $quantidade < 1) {
            return $this->error('invalid', 'Quantidade deve ser >= 1.', 400);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $stock = Stock::find()->where(['material_id' => $material->id])->one();
            if (!$stock) {
                $stock = new Stock();
                $stock->material_id = $material->id;
                $stock->quantidade_atual = 0;
                if (!$stock->save(false)) {
                    $transaction->rollBack();
                    return $this->error('stock_create_failed', 'Erro ao criar stock.', 500);
                }
            }
            $stockAtual = (int)$stock->quantidade_atual;
            if ($stockAtual < $quantidade) {
                $transaction->rollBack();
                return $this->error('insufficient_stock', 'Stock insuficiente.', 400, ['stockAtual' => $stockAtual]);
            }
            $userId = Yii::$app->user->id;
            $mov = new Movimento();
            $mov->material_id = $material->id;
            $mov->user_id = $userId;
            $mov->tipo = 'Saida';
            $mov->quantidade = $quantidade;
            $mov->data_movimentacao = date('Y-m-d H:i:s');
            $mov->origem = $observacoes;
            if (!$mov->save()) {
                $transaction->rollBack();
                return $this->error('save_failed', 'Erro ao registar movimento.', 500, $mov->getErrors());
            }
            $stock->quantidade_atual -= $quantidade;
            if (!$stock->save(false)) {
                $transaction->rollBack();
                return $this->error('stock_update_failed', 'Erro ao atualizar stock.', 500);
            }
            $transaction->commit();
            Yii::$app->response->statusCode = 201;
            return [
                'ok' => true,
                'movementId' => $mov->id,
                'newStock' => (int)$stock->quantidade_atual,
            ];
        } catch (\Exception $e) {
            if ($transaction->isActive) $transaction->rollBack();
            return $this->error('exception', 'Erro inesperado.', 500, $e->getMessage());
        }
    }
}
