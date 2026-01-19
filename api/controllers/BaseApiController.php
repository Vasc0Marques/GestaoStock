<?php

namespace api\controllers;

use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class BaseApiController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // ContentNegotiator para garantir JSON
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // CORS
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        // Auth (exceto login e users)
        if (!in_array($this->id, ['auth', 'default', 'users'])) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::class,
                'except' => ['options'],
            ];
        }

        return $behaviors;
    }

    // Helper para resposta de sucesso
    protected function success($data, $status = 200)
    {
        Yii::$app->response->statusCode = $status;
        return $data;
    }

    // Helper para resposta de erro
    protected function error($code, $message, $status = 400, $details = null)
    {
        Yii::$app->response->statusCode = $status;
        $err = [
            'code' => $code,
            'message' => $message,
        ];
        if ($details !== null) {
            $err['details'] = $details;
        }
        return ['error' => $err];
    }

    // Helper para resposta 403 Forbidden
    protected function forbid($message = 'Acesso negado')
    {
        return $this->error('forbidden', $message, 403);
    }

    /**
     * Verifica se o user tem um dos roles permitidos
     * @param array|string $roles
     * @return bool
     */
    protected function checkAccessRole($roles)
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return false;
        }
        $role = $user->cargo ?? null;
        if (is_array($roles)) {
            return in_array($role, $roles);
        }
        return $role === $roles;
    }
}
