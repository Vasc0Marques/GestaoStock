<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Encomenda;
use yii\data\ActiveDataProvider;

class EncomendasTerminalController extends Controller
{
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
