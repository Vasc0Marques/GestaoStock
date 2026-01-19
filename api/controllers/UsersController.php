<?php
namespace api\controllers;

use common\models\User;
use yii\rest\Controller;

class UsersController extends BaseApiController
{
    public function actionIndex()
    {
        // Busca todos os utilizadores ativos
        $users = \common\models\User::find()->where(['status' => \common\models\User::STATUS_ACTIVE])->all();
        $result = [];
        foreach ($users as $user) {
            $result[] = [
                'id' => $user->id,
                'nome' => method_exists($user, 'getNomeCompleto') ? $user->getNomeCompleto() : $user->username,
                'username' => $user->username,
            ];
        }
        return $result;
    }
}
