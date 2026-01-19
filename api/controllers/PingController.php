<?php
namespace api\controllers;

use yii\web\Controller;
use yii\web\Response;

class PingController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['ok' => true];
    }
}
