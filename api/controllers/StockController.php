<?php

namespace api\controllers;

use common\models\Stock;

class StockController extends BaseApiController
{
    /**
     * GET /api/stock/{id}
     * @param int $id material_id
     * @return array
     */
    public function actionView($id)
    {
        $stock = Stock::find()->where(['material_id' => $id])->one();
        if (!$stock) {
            return $this->error('not_found', 'Stock não encontrado.', 404);
        }

        $material = $stock->material;
        if (!$material) {
            return $this->error('not_found', 'Material não encontrado.', 404);
        }

        $zona = $material->zona ? $material->zona->nome_zona : null;

        return $this->success([
            'material' => $material->nome_material,
            'quantidade' => (int)$stock->quantidade_atual,
            'zona' => $zona,
        ]);
    }
}
