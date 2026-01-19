<?php

namespace api\controllers;

use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

class DefaultController extends BaseApiController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        return $this->success(['message' => 'API is working!']);
    }
}
