<?php

namespace backend\controllers;

use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['administrador'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Gera auth_key se não existir
                if (empty($model->auth_key)) {
                    $model->generateAuthKey();
                }
                
                // Trata password: só gera hash se foi preenchida
                if (!empty($model->password_hash)) {
                    $model->setPassword($model->password_hash);
                } else {
                    $model->addError('password_hash', 'Password é obrigatória para novos utilizadores.');
                    return $this->render('create', ['model' => $model]);
                }
                
                if ($model->save()) {
                    // RBAC: atribui role
                    $auth = \Yii::$app->authManager;
                    $auth->revokeAll($model->id);
                    $role = $auth->getRole($model->cargo);
                    if ($role) {
                        $auth->assign($role, $model->id);
                    }
                    
                    \Yii::$app->session->setFlash('success', 'Utilizador criado com sucesso!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
            $model->status = User::STATUS_ACTIVE; // Define como ativo por padrão
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldPasswordHash = $model->password_hash;

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Se o campo password_hash vier vazio, mantém o antigo
            if (empty($model->password_hash)) {
                $model->password_hash = $oldPasswordHash;
            } else {
                // Se mudou, gera novo hash
                $model->setPassword($model->password_hash);
            }
            
            if ($model->save()) {
                // RBAC: atualiza role
                $auth = \Yii::$app->authManager;
                $auth->revokeAll($model->id);
                $role = $auth->getRole($model->cargo);
                if ($role) {
                    $auth->assign($role, $model->id);
                }
                
                \Yii::$app->session->setFlash('success', 'Utilizador atualizado com sucesso!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Limpa o password_hash para não aparecer no form
        $model->password_hash = '';

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
