<?php

namespace frontend\controllers;

use yii\web\Controller;

class EncomendasTerminalController extends Controller
{
    public function actionConsultar()
    {
        return $this->render('consultar-encomendas');
    }
}
