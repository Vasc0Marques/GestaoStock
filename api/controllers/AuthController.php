<?php

namespace api\controllers;

use Yii;
use common\models\User;

class AuthController extends BaseApiController{
    public function actionVerifyPin()
    {
        $data = \Yii::$app->request->getBodyParams();
        $userId = $data['userId'] ?? null;
        $pin = $data['pin'] ?? null;

        // Validar userId
        if ($userId === null || !is_numeric($userId)) {
            \Yii::$app->response->statusCode = 400;
            return ['ok' => false, 'message' => 'userId obrigatório e numérico.'];
        }
        // Validar pin
        if ($pin === null || !is_string($pin) || !preg_match('/^[0-9]{4}$/', $pin)) {
            \Yii::$app->response->statusCode = 400;
            return ['ok' => false, 'message' => 'PIN obrigatório (4 dígitos).'];
        }

        // Buscar utilizador por id
        $user = \common\models\User::findOne(['id' => $userId, 'status' => \common\models\User::STATUS_ACTIVE]);
        if (!$user) {
            \Yii::$app->response->statusCode = 404;
            return ['ok' => false, 'message' => 'Utilizador não encontrado.'];
        }

        // Validar PIN para esse utilizador
        if (!isset($user->pin) || $user->pin !== $pin) {
            \Yii::$app->response->statusCode = 401;
            return ['ok' => false, 'message' => 'PIN inválido'];
        }

        return ['ok' => true];
    }
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Remove autenticação para login
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    public function actionLogin()
    {
        $data = Yii::$app->request->getBodyParams();
        $userId = $data['userId'] ?? null;
        $pin = $data['pin'] ?? null;

        // Validar userId
        if ($userId === null || !is_numeric($userId)) {
            return $this->error('userId_required', 'userId obrigatório e numérico.', 400);
        }
        // Validar pin
        if ($pin === null || !is_string($pin) || !preg_match('/^[0-9]{4}$/', $pin)) {
            return $this->error('pin_required', 'PIN obrigatório (4 dígitos).', 400);
        }

        // Buscar utilizador por id
        $user = User::findOne(['id' => $userId, 'status' => User::STATUS_ACTIVE]);
        if (!$user) {
            return $this->error('user_not_found', 'Utilizador não encontrado.', 404);
        }

        // Validar PIN para esse utilizador
        if (!isset($user->pin) || $user->pin !== $pin) {
            return $this->error('invalid_pin', 'PIN inválido.', 401);
        }

        $token = $user->generateApiToken();

        return $this->success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'nome' => method_exists($user, 'getNomeCompleto') ? $user->getNomeCompleto() : $user->username,
                'username' => $user->username,
            ],
        ], 200);
    }
}
