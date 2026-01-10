<?php

namespace backend\controllers;

use common\models\Fornecedor;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FornecedorController implements the CRUD actions for Fornecedor model.
 */
class FornecedorController extends BaseController
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
                            'roles' => ['administrador', 'gestor'],
                        ],
                    ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['site/access-denied']);
                },
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
     * Lists all Fornecedor models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Fornecedor::find(),
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
     * Displays a single Fornecedor model and handles image upload/update.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            $model->imagemFile = \yii\web\UploadedFile::getInstance($model, 'imagemFile');
            if ($model->imagemFile) {
                $folder = \Yii::getAlias('@backend/web/uploads/');
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                $filename = 'fornecedor_' . $model->id . '_' . time() . '.' . $model->imagemFile->extension;
                $fullPath = $folder . $filename;
                if ($model->imagemFile->saveAs($fullPath)) {
                    $model->imagem = 'uploads/' . $filename;
                }
            }
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fornecedor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imagemFile = \yii\web\UploadedFile::getInstance($model, 'imagemFile');
            if ($model->imagemFile) {
                $folder = \Yii::getAlias('@backend/web/uploads/');
                if (!is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
                $filename = 'fornecedor_' . $model->id . '_' . time() . '.' . $model->imagemFile->extension;
                $fullPath = $folder . $filename;
                if ($model->imagemFile->saveAs($fullPath)) {
                    $model->imagem = 'uploads/' . $filename;
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Fornecedor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fornecedor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fornecedor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fornecedor::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
