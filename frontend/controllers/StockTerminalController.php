<?php

namespace frontend\controllers;

use yii\web\Controller;

class StockTerminalController extends Controller
{
    public function actionConsultar()
    {
        return $this->render('consultar-stock');
    }

    public function actionSaida()
    {
        return $this->render('saida-stock');
    }
}
