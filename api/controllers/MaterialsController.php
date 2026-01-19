<?php

namespace api\controllers;

use Yii;
use yii\filters\VerbFilter;
use common\models\Material;
use common\models\Stock;
use common\models\Movimento;
use yii\web\NotFoundHttpException;

class MaterialsController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Exigir autenticação Bearer exceto OPTIONS
        if (isset($behaviors['authenticator'])) {
            $behaviors['authenticator']['except'] = ['options'];
        }
        return $behaviors;
    }

    // GET /materials/search?codigo=XXX
    public function actionSearch($codigo)
    {
        $material = Material::find()->where(['codigo' => $codigo])->one();
        if (!$material) {
            return $this->error('not_found', 'Material não encontrado.', 404);
        }
        return $this->formatMaterial($material);
    }

    // GET /materials/{codigo}
    public function actionView($codigo)
    {
        $material = Material::find()->where(['codigo' => $codigo])->one();
        if (!$material) {
            return $this->error('not_found', 'Material não encontrado.', 404);
        }
        return $this->formatMaterial($material);
    }

    // GET /materials/{codigo}/movements?limit=50
    public function actionMovements($codigo, $limit = 50)
    {
        $material = Material::find()->where(['codigo' => $codigo])->one();
        if (!$material) {
            return $this->error('not_found', 'Material não encontrado.', 404);
        }
        $movs = Movimento::find()
            ->where(['material_id' => $material->id])
            ->orderBy(['data_movimentacao' => SORT_DESC])
            ->limit((int)$limit)
            ->all();
        $result = [];
        foreach ($movs as $mov) {
            $result[] = [
                'id' => $mov->id,
                'tipo' => strtolower($mov->tipo),
                'quantidade' => (int)$mov->quantidade,
                'data' => $mov->data_movimentacao,
                'materialCodigo' => $material->codigo,
                'observacoes' => $mov->origem !== null ? $mov->origem : null,
            ];
        }
        return $result;
    }

    // Helper para formatar material para Android
    protected function formatMaterial($material)
    {
        $stock = Stock::find()->where(['material_id' => $material->id])->one();
        return [
            'id' => (int)$material->id,
            'codigo' => $material->codigo,
            'nome' => $material->nome_material,
            'unidade' => $material->unidade_medida,
            'stockAtual' => $stock ? (int)$stock->quantidade_atual : 0,
        ];
    }
}
