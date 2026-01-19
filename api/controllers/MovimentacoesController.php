<?php

namespace api\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use common\models\Movimento;

class MovimentacoesController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /**
     * GET /api/movimentacoes?data_inicial=YYYY-MM-DD&data_final=YYYY-MM-DD&tipo=entrada|saida&page=1&per_page=20
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $data_inicial = $request->get('data_inicial');
        $data_final = $request->get('data_final');
        $tipo = $request->get('tipo');
        $page = max(1, (int)$request->get('page', 1));
        $perPage = min(100, max(1, (int)$request->get('per_page', 20)));

        // Validação de datas
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
            return $this->asJson(['error' => implode('; ', $errors)]);
        }

        $query = Movimento::find()->joinWith('material');

        if ($data_inicial) {
            $query->andWhere(['>=', 'data_movimentacao', $data_inicial . ' 00:00:00']);
        }
        if ($data_final) {
            $query->andWhere(['<=', 'data_movimentacao', $data_final . ' 23:59:59']);
        }
        if ($tipo) {
            $tipoDb = ucfirst(strtolower($tipo)); // 'Entrada' ou 'Saida'
            $query->andWhere(['tipo' => $tipoDb]);
        }

        $query->orderBy(['data_movimentacao' => SORT_DESC]);

        // Paginação simples
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

        return [
            'total' => (int)$total,
            'page' => $page,
            'per_page' => $perPage,
            'data' => $result,
        ];
    }
}
