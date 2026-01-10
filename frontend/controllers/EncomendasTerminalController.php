<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Encomenda;
use yii\data\ActiveDataProvider;

class EncomendasTerminalController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['consultar', 'view'],
                'rules' => [
                    [
                        'actions' => ['consultar', 'view'],
                        'allow' => true,
                        'roles' => ['administrador', 'gestor', 'operador'],
                    ],
                ],
            ],
        ];
    }
    public function actionConsultar()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Encomenda::find()->with('fornecedor'),
            'pagination' => false,
        ]);
        return $this->render('consultar-encomendas', [
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {
        $model = \common\models\Encomenda::find()->with(['fornecedor', 'user', 'encomendaLinhas.material'])->where(['id' => $id])->one();
        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Encomenda não encontrada.');
        }
        return $this->render('view-encomenda', [
            'model' => $model,
        ]);
    }
}
