<?php

namespace api\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use common\models\Encomenda;

class EncomendaController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionView($id)
    {
        $model = Encomenda::find()->with(['fornecedor', 'encomendaLinhas.material'])->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('Encomenda não encontrada.');
        }
        return $model->toArray([
            'id', 'estado', 'data_encomenda', 'data_rececao', 'total',
            'fornecedor' => function($model) {
                return $model->fornecedor ? $model->fornecedor->toArray(['id', 'nome_fornecedor']) : null;
            },
            'encomendaLinhas' => function($model) {
                return array_map(function($linha) {
                    return $linha->toArray(['id', 'material_id', 'nome_material', 'quantidade', 'preco_unitario', 'subtotal']);
                }, $model->encomendaLinhas);
            }
        ]);
    }
}
