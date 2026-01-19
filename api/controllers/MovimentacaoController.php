<?php

namespace api\controllers;

use Yii;
use common\models\Movimento;
use common\models\Stock;

class MovimentacaoController extends BaseApiController
{
    public function actionIndex($data_inicial = null, $data_final = null, $tipo = null)
    {
        if (!$this->checkAccessRole(['administrador', 'gestor', 'operador'])) {
            return $this->forbid();
        }

        $request = Yii::$app->request;
        $page = max(1, (int)$request->get('page', 1));
        $perPage = min(100, max(1, (int)$request->get('per_page', 20)));

        $errors = [];
        if ($data_inicial && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_inicial)) {
            $errors[] = 'data_inicial inválida (YYYY-MM-DD)';
        }
        if ($data_final && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_final)) {
            $errors[] = 'data_final inválida (YYYY-MM-DD)';
        }
        if ($tipo && !in_array(strtolower($tipo), ['entrada', 'saida'])) {
            $errors[] = 'tipo deve ser entrada ou saida';
        }
        if ($errors) {
            return $this->error('validation', implode('; ', $errors), 400);
        }

        $query = Movimento::find()->joinWith('material');
        if ($data_inicial) {
            $query->andWhere(['>=', 'data_movimentacao', $data_inicial . ' 00:00:00']);
        }
        if ($data_final) {
            $query->andWhere(['<=', 'data_movimentacao', $data_final . ' 23:59:59']);
        }
        if ($tipo) {
            $tipoDb = ucfirst(strtolower($tipo));
            $query->andWhere(['tipo' => $tipoDb]);
        }
        $query->orderBy(['data_movimentacao' => SORT_DESC]);

        $total = $query->count();
        $query->offset(($page - 1) * $perPage)->limit($perPage);

        $movs = $query->all();
        $result = [];
        foreach ($movs as $mov) {
            $result[] = [
                'id' => $mov->id,
                'material' => $mov->material ? $mov->material->nome_material : null,
                'tipo' => strtolower($mov->tipo),
                'quantidade' => (int)$mov->quantidade,
            ];
        }

        return $this->success([
            'total' => (int)$total,
            'page' => $page,
            'per_page' => $perPage,
            'data' => $result,
        ]);
    }

    public function actionCreate()
    {
        if (!$this->checkAccessRole(['administrador', 'gestor', 'operador'])) {
            return $this->forbid();
        }

        $request = Yii::$app->request;
        $user = Yii::$app->user->identity;

        $data = $request->getBodyParams();
        $materialId = $data['id_material'] ?? null;
        $tipo = strtolower($data['tipo'] ?? '');
        $quantidade = isset($data['quantidade']) ? (int)$data['quantidade'] : null;
        $origem = $data['origem'] ?? null;

        if (!$materialId || !in_array($tipo, ['entrada', 'saida']) || !$quantidade || $quantidade <= 0) {
            return $this->error('validation', 'input_invalido', 400);
        }

        $material = \common\models\Material::findOne($materialId);
        if (!$material) {
            return $this->error('not_found', 'material_nao_encontrado', 404);
        }

        $stock = Stock::findOne(['material_id' => $materialId]);
        if (!$stock) {
            if ($tipo === 'entrada') {
                $stock = new Stock();
                $stock->material_id = $materialId;
                $stock->quantidade_atual = 0;
            } else {
                return $this->error('stock_insuficiente', 'stock_insuficiente', 400);
            }
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($tipo === 'saida') {
                if ($stock->quantidade_atual < $quantidade) {
                    $transaction->rollBack();
                    return $this->error('stock_insuficiente', 'stock_insuficiente', 400);
                }
                $stock->quantidade_atual -= $quantidade;
            } else {
                $stock->quantidade_atual += $quantidade;
            }
            $stock->ultima_atualizacao = date('Y-m-d H:i:s');
            $stock->save(false);

            $mov = new Movimento();
            $mov->material_id = $materialId;
            $mov->user_id = $user->id;
            $mov->tipo = $tipo === 'entrada' ? Movimento::TIPO_ENTRADA : Movimento::TIPO_SAIDA;
            $mov->quantidade = $quantidade;
            $mov->data_movimentacao = date('Y-m-d H:i:s');
            $mov->origem = $origem;
            if (!$mov->validate()) {
                $transaction->rollBack();
                return $this->error('validation', 'Dados inválidos', 400, $mov->getFirstErrors());
            }
            $mov->save(false);

            $transaction->commit();

            Yii::$app->response->statusCode = 201;
            return $this->success([
                'status' => 'success',
                'movimento_id' => $mov->id,
                'stock_atual' => (int)$stock->quantidade_atual,
            ], 201);
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return $this->error('internal', 'erro_interno', 500, $e->getMessage());
        }
    }
}
