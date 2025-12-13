<?php

namespace backend\controllers;

use common\models\Encomenda;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncomendaController implements the CRUD actions for Encomenda model.
 */
class EncomendaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Encomenda models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Encomenda::find(),
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
     * Displays a single Encomenda model.
     * @param int $id ID
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
     * Creates a new Encomenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Encomenda();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Encomenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $estadoAnterior = $model->estado;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Se mudou para Recebida e não era Recebida antes
            if ($estadoAnterior !== 'Recebida' && $model->estado === 'Recebida') {
                foreach ($model->encomendaLinhas as $linha) {
                    // Atualiza o stock
                    $stock = \common\models\Stock::findOne(['material_id' => $linha->material_id]);
                    if ($stock) {
                        $stock->quantidade_atual += $linha->quantidade;
                        $stock->ultima_atualizacao = date('Y-m-d H:i:s');
                        $stock->save(false);
                    } else {
                        $novoStock = new \common\models\Stock();
                        $novoStock->material_id = $linha->material_id;
                        $novoStock->quantidade_atual = $linha->quantidade;
                        $novoStock->ultima_atualizacao = date('Y-m-d H:i:s');
                        $novoStock->save(false);
                    }

                    // Criar movimentação de entrada
                    $mov = new \common\models\Movimento();
                    $mov->material_id = $linha->material_id;
                    $mov->user_id = $model->user_id;
                    $mov->tipo = \common\models\Movimento::TIPO_ENTRADA;
                    $mov->quantidade = $linha->quantidade;
                    $mov->data_movimentacao = date('Y-m-d H:i:s');
                    $mov->origem = 'Encomenda #' . $model->id;
                    $mov->save(false);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Encomenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Elimina todas as linhas associadas a esta encomenda
        \common\models\EncomendaLinha::deleteAll(['encomenda_id' => $model->id]);

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Encomenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Encomenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Encomenda::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
