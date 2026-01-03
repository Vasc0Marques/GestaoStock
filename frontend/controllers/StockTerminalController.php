<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Stock;
use yii\data\ActiveDataProvider;

class StockTerminalController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['consultar', 'saida'],
                'rules' => [
                    [
                        'actions' => ['consultar'],
                        'allow' => true,
                        'roles' => ['gestor', 'operador'],
                    ],
                    [
                        'actions' => ['saida'],
                        'allow' => true,
                        'roles' => ['gestor'],
                    ],
                ],
            ],
        ];
    }
    public function actionConsultar()
    {
        // Corrige o nome da tabela para 'materiais' (não 'material')
        $query = Stock::find()->joinWith(['material']);
        $codigo = \Yii::$app->request->get('codigo');
        if ($codigo) {
            // Usa o nome correto da tabela: 'materiais'
            $query->andWhere(['materiais.codigo' => $codigo]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        return $this->render('consultar-stock', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaida()
    {
        return $this->render('saida-stock');
    }
}