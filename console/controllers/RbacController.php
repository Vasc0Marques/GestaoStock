<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Roles
        $gestor = $auth->createRole('gestor');
        $auth->add($gestor);
        $operador = $auth->createRole('operador');
        $auth->add($operador);

        // Backend permissions (Gestor only)
        $backendControllers = [
            'fornecedor', 'encomenda-linha', 'encomenda', 'categoria', 'material-fornecedor',
            'material', 'zona', 'user', 'stock', 'movimento', 'site'
        ];
        $backendActions = ['index', 'view', 'create', 'update', 'delete', 'login', 'logout'];
        foreach ($backendControllers as $controller) {
            foreach ($backendActions as $action) {
                $permName = "backend_{$controller}_{$action}";
                $permission = $auth->createPermission($permName);
                $auth->add($permission);
                $auth->addChild($gestor, $permission);
            }
        }

        // Frontend permissions
        // Gestor: all actions; Operador: only consultar/visualizar
        $frontendControllers = [
            'site' => ['index', 'login', 'logout', 'contact', 'about', 'signup', 'request-password-reset', 'reset-password', 'verify-email', 'resend-verification-email'],
            'stock-terminal' => ['consultar', 'saida'],
            'encomendas-terminal' => ['consultar']
        ];
        foreach ($frontendControllers as $controller => $actions) {
            foreach ($actions as $action) {
                $permName = "frontend_{$controller}_{$action}";
                $permission = $auth->createPermission($permName);
                $auth->add($permission);
                $auth->addChild($gestor, $permission);
                // Operador só pode consultar/visualizar
                if (in_array($action, ['consultar', 'index', 'about', 'contact'])) {
                    $auth->addChild($operador, $permission);
                }
            }
        }
        echo "RBAC inicializado com sucesso!\n";
    }
}
