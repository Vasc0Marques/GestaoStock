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
                'only' => ['consultar'],
                'rules' => [
                    [
                        'actions' => ['consultar'],
                        'allow' => true,
                        'roles' => ['gestor', 'operador'],
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
}
